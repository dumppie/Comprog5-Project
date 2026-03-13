@extends('layouts.base')
@section('title', 'Edit Review')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="font-serif mb-0" style="color: var(--pastry-brown);">Edit Your Review</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('reviews.update', $review) }}">
                            @csrf
                            @method('PUT')
                            
                            <!-- Product Info -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Product</label>
                                <div class="form-control-plaintext">
                                    {{ $review->product->name }}
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="mb-4">
                                <label for="rating" class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="form-check-label">
                                            <input type="radio" name="rating" value="{{ $i }}" 
                                                   class="d-none" {{ $review->rating == $i ? 'checked' : '' }}
                                                   onchange="updateStars({{ $i }})">
                                            <span class="star-rating" data-rating="{{ $i }}" style="font-size: 2rem; cursor: pointer; color: {{ $review->rating >= $i ? '#ffc107' : '#dee2e6' }};">★</span>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Comment -->
                            <div class="mb-4">
                                <label for="comment" class="form-label fw-bold">Comment</label>
                                <textarea name="comment" id="comment" rows="4" class="form-control" 
                                          placeholder="Share your experience with this product...">{{ old('comment', $review->comment) }}</textarea>
                                <small class="text-muted">Maximum 1000 characters</small>
                                @error('comment')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-pastry">Update Review</button>
                                <a href="{{ route('products.show', $review->product_id) }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function updateStars(rating) {
        document.querySelectorAll('.star-rating').forEach((star, index) => {
            star.style.color = (index < rating) ? '#ffc107' : '#dee2e6';
        });
    }
    </script>
    @endpush
@endsection
