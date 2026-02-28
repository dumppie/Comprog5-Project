<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ShopController extends Controller
{
    /** Product listing for customers; add to cart from here (FR4.1) */
    public function index(): View
    {
        $products = Product::whereNull('deleted_at')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('shop.index', compact('products'));
    }
}
