<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Only admin can access, will be handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:bread,cakes,pastries,cookies,pies,tarts,muffins,croissants,donuts,buns',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'stock_quantity' => 'required|integer|min:0|max:999999',
            'thumbnail_photo' => 'nullable|mimes:jpeg,jpg,png|max:2048', // 2MB max
            'photos' => 'nullable|array|max:10', // Max 10 photos
            'photos.*' => 'mimes:jpeg,jpg,png|max:2048', // 2MB each
            'photo_captions' => 'nullable|array',
            'photo_captions.*' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive'
        ];

        // For update requests, make fields optional if not provided
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'] = 'sometimes|string|max:255';
            $rules['category'] = 'sometimes|string|in:bread,cakes,pastries,cookies,pies,tarts,muffins,croissants,donuts,buns';
            $rules['description'] = 'sometimes|nullable|string|max:2000';
            $rules['price'] = 'sometimes|numeric|min:0|max:999999.99';
            $rules['stock_quantity'] = 'sometimes|integer|min:0|max:999999';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name may not be greater than 255 characters.',
            'category.required' => 'Product category is required.',
            'category.in' => 'Selected category is invalid.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Product price must be a number.',
            'price.min' => 'Product price must be at least 0.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'stock_quantity.min' => 'Stock quantity must be at least 0.',
            'thumbnail_photo.mimes' => 'Thumbnail must be a JPEG or PNG file.',
            'thumbnail_photo.max' => 'Thumbnail may not be greater than 2MB.',
            'photos.max' => 'You may upload a maximum of 10 photos.',
            'photos.*.mimes' => 'All photos must be JPEG or PNG files.',
            'photos.*.max' => 'Each photo may not be greater than 2MB.',
            'status.in' => 'Selected status is invalid.'
        ];
    }
}
