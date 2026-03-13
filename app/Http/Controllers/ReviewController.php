<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $product = Product::findOrFail($request->product_id);

        // FR9.1: Only customers who have purchased can post reviews
        if (!$user->hasPurchasedProduct($request->product_id)) {
            abort(403, 'You can only review products you have purchased.');
        }

        // Check if user already reviewed this product
        if ($user->hasReviewedProduct($request->product_id)) {
            return redirect()->back()
                ->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'product_id' => $request->product_id,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()
            ->with('success', 'Your review has been submitted successfully!');
    }

    public function edit(Request $request, Review $review): View
    {
        // FR9.2: Only review author can edit
        if (!$review->isAuthor($request->user())) {
            abort(403, 'Unauthorized action.');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        // FR9.2: Only review author can update
        if (!$review->isAuthor($request->user())) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('products.show', $review->product_id)
            ->with('success', 'Your review has been updated successfully!');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $this->authorize('delete', $review);

        $productId = $review->product_id;
        $review->delete();

        // FR9.4: Average rating will be recalculated automatically
        return redirect()->back()
            ->with('success', 'Review has been deleted successfully!');
    }
}
