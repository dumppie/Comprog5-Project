<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Http\Requests\ProductRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['photos', 'thumbnail'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = array_keys(config('categories.product_categories'));
        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::create([
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'status' => $request->status ?? 'active'
            ]);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail_photo')) {
                $thumbnailPath = $request->file('thumbnail_photo')
                    ->store('products/thumbnails', 'public');
                $product->update(['thumbnail_photo' => $thumbnailPath]);
            }

            // Handle multiple photos upload
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    if ($photo->isValid()) {
                        $photoPath = $photo->store('products/photos', 'public');
                        ProductPhoto::create([
                            'product_id' => $product->id,
                            'photo_path' => $photoPath,
                            'caption' => $request->photo_captions[$index] ?? null,
                            'is_thumbnail' => false
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product "' . $product->name . '" has been created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function show(Product $product): View
    {
        $product->load(['photos', 'thumbnail']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = array_keys(config('categories.product_categories'));
        $product->load(['photos', 'thumbnail']);
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            DB::beginTransaction();

            $product->update([
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'status' => $request->status ?? $product->status
            ]);

            // Handle thumbnail update
            if ($request->hasFile('thumbnail_photo')) {
                // Delete old thumbnail
                if ($product->thumbnail_photo) {
                    Storage::disk('public')->delete($product->thumbnail_photo);
                }
                
                $thumbnailPath = $request->file('thumbnail_photo')
                    ->store('products/thumbnails', 'public');
                $product->update(['thumbnail_photo' => $thumbnailPath]);
            }

            // Handle new photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    if ($photo->isValid()) {
                        $photoPath = $photo->store('products/photos', 'public');
                        ProductPhoto::create([
                            'product_id' => $product->id,
                            'photo_path' => $photoPath,
                            'caption' => $request->photo_captions[$index] ?? null,
                            'is_thumbnail' => false
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => $product->load(['photos', 'thumbnail'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product moved to trash successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore($id): JsonResponse
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            $product->restore();
            return response()->json([
                'success' => true,
                'message' => 'Product restored successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceDelete($id): JsonResponse
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            
            // Delete associated photos
            $product->photos()->each(function ($photo) {
                if ($photo->photo_path) {
                    Storage::disk('public')->delete($photo->photo_path);
                }
                $photo->delete();
            });

            // Delete thumbnail
            if ($product->thumbnail_photo) {
                Storage::disk('public')->delete($product->thumbnail_photo);
            }

            $product->forceDelete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product permanently deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trash(): View
    {
        $trashedProducts = Product::onlyTrashed()
            ->with(['photos', 'thumbnail'])
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('admin.products.trash_simple', compact('trashedProducts'));
    }

    public function bulkRestore(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $productIds = $request->product_ids;
            $restoredCount = 0;
            
            foreach ($productIds as $productId) {
                $product = Product::withTrashed()->find($productId);
                if ($product && $product->trashed()) {
                    $product->restore();
                    $restoredCount++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully restored {$restoredCount} products"
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkForceDelete(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $productIds = $request->product_ids;
            $deletedCount = 0;
            
            foreach ($productIds as $productId) {
                $product = Product::withTrashed()->find($productId);
                if ($product && $product->trashed()) {
                    // Delete associated photos
                    if ($product->thumbnail_photo) {
                        Storage::disk('public')->delete($product->thumbnail_photo);
                    }
                    
                    foreach ($product->photos as $photo) {
                        Storage::disk('public')->delete($photo->photo_path);
                        $photo->delete();
                    }
                    
                    $product->forceDelete();
                    $deletedCount++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully permanently deleted {$deletedCount} products"
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function emptyTrash(): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $trashedProducts = Product::onlyTrashed()->get();
            $deletedCount = 0;
            
            foreach ($trashedProducts as $product) {
                // Delete associated photos
                if ($product->thumbnail_photo) {
                    Storage::disk('public')->delete($product->thumbnail_photo);
                }
                
                foreach ($product->photos as $photo) {
                    Storage::disk('public')->delete($photo->photo_path);
                    $photo->delete();
                }
                
                $product->forceDelete();
                $deletedCount++;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully emptied trash. {$deletedCount} products permanently deleted."
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to empty trash: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importView(): View
    {
        return view('admin.products.import');
    }

    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls|max:10240' // 10MB max
            ]);

            $import = new ProductsImport();
            Excel::import($import, $request->file('excel_file'));

            return response()->json([
                'success' => true,
                'message' => 'Products imported successfully',
                'imported' => $import->getImportedCount(),
                'errors' => $import->getErrors(),
                'error_report' => $import->hasErrors() ? route('products.error-report.download') : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $productIds = $request->product_ids;
            $deletedCount = 0;
            
            foreach ($productIds as $productId) {
                $product = Product::find($productId);
                if ($product) {
                    // Delete associated photos
                    if ($product->thumbnail_photo) {
                        Storage::disk('public')->delete($product->thumbnail_photo);
                    }
                    
                    foreach ($product->photos as $photo) {
                        Storage::disk('public')->delete($photo->photo_path);
                        $photo->delete();
                    }
                    
                    $product->delete();
                    $deletedCount++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} products"
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadErrorReport(): JsonResponse
    {
        $errors = session('import_errors', []);
        
        if (empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'No errors to download'
            ]);
        }

        $filename = 'product_import_errors_' . date('Y_m_d_H_i_s') . '.json';
        $filepath = storage_path('app/temp/' . $filename);
        
        file_put_contents($filepath, json_encode($errors, JSON_PRETTY_PRINT));
        
        return response()->download($filepath, $filename)->deleteFileAfterSend();
    }
}
