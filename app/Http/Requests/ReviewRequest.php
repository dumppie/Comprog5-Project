<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $productId = $this->product_id;

        // FR9.1: Only customers who have purchased can post reviews
        if (!$user->hasPurchasedProduct($productId)) {
            return false;
        }

        // Only one review per product
        if ($user->hasReviewedProduct($productId)) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Please select a rating.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot be more than 5 stars.',
            'comment.max' => 'Comment cannot exceed 1000 characters.',
        ];
    }
}
