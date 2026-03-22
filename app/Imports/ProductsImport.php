<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithStartRow
{
    private $importedCount = 0;
    private $errors = [];
    private $currentRow = 2; // Start from row 2 (after headers)

    public function startRow(): int
    {
        return 2;
    }

    public function getCurrentRow(): int
    {
        return $this->currentRow;
    }

    public function model(array $row): ?Product
    {
        try {
            $product = Product::create([
                'name' => $row['name'] ?? null,
                'category' => $row['category'] ?? 'bread',
                'description' => $row['description'] ?? null,
                'price' => $this->parsePrice($row['price'] ?? 0),
                'stock_quantity' => $this->parseInteger($row['stock_quantity'] ?? 0),
                'status' => 'active'
            ]);

            $this->importedCount++;
            $this->currentRow++;
            return $product;

        } catch (\Exception $e) {
            $this->errors[] = [
                'row' => $this->currentRow,
                'data' => $row,
                'error' => $e->getMessage(),
                'field' => 'general'
            ];
            $this->currentRow++;
            return null;
        }
    }

    public function rules(): array
    {
        $categories = array_keys(config('categories.product_categories'));
        $categoryString = implode(',', $categories);
        
        return [
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|in:' . $categoryString,
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0'
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'Product name is required in row :row',
            'category.in' => 'Invalid category in row :row',
            'price.required' => 'Price is required in row :row',
            'price.numeric' => 'Price must be a number in row :row',
            'stock_quantity.required' => 'Stock quantity is required in row :row',
            'stock_quantity.integer' => 'Stock quantity must be a whole number in row :row'
        ];
    }

    public function onError(\Throwable $e): void
    {
        $this->errors[] = [
            'row' => $this->currentRow,
            'data' => $e->getTraceAsString(),
            'error' => $e->getMessage(),
            'field' => 'system_error'
        ];
        $this->currentRow++;
    }

    private function parsePrice($value): float
    {
        // Remove currency symbols and convert to float
        return (float) preg_replace('/[^0-9.]/', '', $value);
    }

    private function parseInteger($value): int
    {
        return (int) preg_replace('/[^0-9]/', '', $value);
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
