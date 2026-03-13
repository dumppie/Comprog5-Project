<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['product', 'user']);

        // FR9.3: Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('product', function ($subQ) use ($request) {
                    $subQ->where('name', 'LIKE', "%{$request->search}%");
                })
                ->orWhereHas('user', function ($subQ) use ($request) {
                    $subQ->where('name', 'LIKE', "%{$request->search}%");
                })
                ->orWhere('comment', 'LIKE', "%{$request->search}%");
            });
        }

        // FR9.3: Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review): RedirectResponse
    {
        // FR9.4: Admin can delete any review
        $productId = $review->product_id;
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review has been deleted successfully!');
    }
}
