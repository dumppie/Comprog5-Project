<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = base_path('pastry_shop_products_seed.xlsx');

        if (!file_exists($file)) {
            $this->command->error("Seed file not found: {$file}");
            return;
        }

        $this->command->info('Starting import from: ' . $file);

        $import = new ProductsImport();
        Excel::import($import, $file);

        if ($import->hasErrors()) {
            $this->command->error('Import completed with errors.');
            foreach ($import->getErrors() as $err) {
                $this->command->line('Row ' . ($err['row'] ?? '?') . ': ' . ($err['error'] ?? json_encode($err)));
            }
        } else {
            $this->command->info('Import successful. Imported: ' . $import->getImportedCount());
        }
    }
}
