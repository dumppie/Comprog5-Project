<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class TrashController extends Controller
{
    public function index(): View
    {
        $trashedProducts = Product::onlyTrashed()
            ->with(['photos', 'thumbnail'])
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('admin.trash.index', compact('trashedProducts'));
    }
}
