<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /** Product listing for customers; add to cart from here (FR4.1) */
    public function index(Request $request): View
    {
        $searchTerm = $request->get('search');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $category = $request->get('category');
        
        if ($searchTerm || $minPrice || $maxPrice || $category) {
            // Try to use Scout if available, fallback to basic search
            try {
                if (class_exists('Laravel\Scout\Builder') && $searchTerm) {
                    $products = Product::search($searchTerm);
                    // Apply additional filters for Scout
                    if ($minPrice || $maxPrice) {
                        $products = $products->where('price', '>=', $minPrice ?? 0)
                                           ->where('price', '<=', $maxPrice ?? 999999);
                    }
                    if ($category) {
                        $products = $products->where('category', $category);
                    }
                    $products = $products->where('status', 'active')->get();
                } else {
                    $products = Product::whereNull('deleted_at')
                        ->active()
                        ->search($searchTerm)
                        ->priceRange($minPrice, $maxPrice)
                        ->category($category)
                        ->orderBy('name')
                        ->get();
                }
            } catch (\Exception $e) {
                // Fallback to basic search if Scout fails
                $products = Product::whereNull('deleted_at')
                    ->active()
                    ->search($searchTerm)
                    ->priceRange($minPrice, $maxPrice)
                    ->category($category)
                    ->orderBy('name')
                    ->get();
            }
        } else {
            // Show all active products when no filters
            $products = Product::whereNull('deleted_at')
                ->active()
                ->orderBy('name')
                ->get();
        }

        // Get unique categories for filter dropdown
        $categories = Product::whereNull('deleted_at')
            ->active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('shop.index', compact('products', 'searchTerm', 'minPrice', 'maxPrice', 'category', 'categories'));
    }
}
