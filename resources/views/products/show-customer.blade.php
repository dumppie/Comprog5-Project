@extends('layouts.base')
@section('title', $product->name)
@section('body')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image Gallery -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="color: var(--pastry-brown);">
                        <i class="fas fa-images me-2"></i>Product Gallery
                    </h6>
                </div>
                <div class="card-body p-3">
                    <!-- Main Product Image -->
                    <div class="mb-3">
                        @if($product->thumbnail_photo)
                            <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" 
                                 class="img-fluid rounded shadow" 
                                 style="width: 100%; height: 300px; object-fit: cover; border: 2px solid var(--pastry-sand);"
                                 alt="{{ $product->name }}"
                                 id="mainProductImage">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded bg-light" 
                                 style="width: 100%; height: 300px; border: 2px solid var(--pastry-sand);">
                                <i class="fas fa-bread-slice fa-3x" style="color: var(--pastry-caramel); opacity: 0.5;"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnail Gallery -->
                    @if($product->photos->count() > 1)
                        <div class="row">
                            <h6 class="mb-3" style="color: var(--pastry-brown);">More Photos</h6>
                            @foreach($product->photos as $photo)
                                @if(!$photo->is_thumbnail)
                                    <div class="col-4 mb-2">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                             class="img-thumbnail rounded shadow cursor-pointer" 
                                             style="width: 100%; height: 80px; object-fit: cover; border: 1px solid var(--pastry-sand);"
                                             alt="{{ $photo->caption ?? $product->name }}"
                                             onclick="changeMainImage('{{ asset('storage/' . $photo->photo_path) }}')">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="color: var(--pastry-brown);">
                        <i class="fas fa-info-circle me-2"></i>{{ $product->name }}
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Price and Stock -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="h4 text-success mb-0">{{ $product->formatted_price }}</span>
                                @if($product->stock_quantity <= 5)
                                    <span class="badge bg-warning ms-2">Low Stock</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->stock_quantity }} units available
                            </span>
                            <small class="text-muted d-block">({{ $product->stock_status }})</small>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Category:</strong> <span class="badge" style="background-color: var(--pastry-sand);">{{ ucfirst($product->category) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Product ID:</strong> #{{ $product->id }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($product->description)
                        <div class="mt-3">
                            <h6 style="color: var(--pastry-brown);">
                                <i class="fas fa-align-left me-2"></i>Description
                            </h6>
                            <div class="p-3 rounded" style="background-color: var(--pastry-cream);">
                                <p class="mb-0">{{ $product->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Add to Cart Button -->
                    <div class="mt-4">
                        @auth
                            @if(!Auth::user()->isAdmin())
                                @if($product->stock_quantity > 0)
                                    <form action="{{ route('cart.store') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="input-group" style="max-width: 300px;">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="form-control" placeholder="Quantity">
                                            <button type="submit" class="btn btn-pastry">
                                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>This product is currently out of stock.
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Please <a href="{{ route('login') }}">log in</a> to add this product to your cart.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>Please <a href="{{ route('login') }}">log in</a> to add this product to your cart.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="color: var(--pastry-brown);">
                        <i class="fas fa-star me-2"></i>Customer Reviews
                        @if($product->reviews_count > 0)
                            <span class="badge bg-secondary ms-2">{{ $product->reviews_count }} Reviews</span>
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Average Rating Display -->
                    @if($product->average_rating > 0)
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center">
                                <span class="h2 text-warning mb-0">{{ number_format($product->average_rating, 1) }}</span>
                                <div class="ms-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->average_rating))
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="text-muted">{{ $product->reviews_count }} customer review{{ $product->reviews_count != 1 ? 's' : '' }}</p>
                        </div>
                    @else
                        <div class="text-center mb-4">
                            <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                        </div>
                    @endif

                    <!-- Existing Reviews -->
                    @if($product->reviews->count() > 0)
                        <div class="reviews-list">
                            @foreach($product->reviews as $review)
                                <div class="review-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center mb-2">
                                                <strong>{{ $review->user->name }}</strong>
                                                <div class="ms-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <small class="text-muted ms-1">{{ $review->rating }}/5</small>
                                                </div>
                                            </div>
                                            <p class="mb-2">{{ $review->comment }}</p>
                                            <small class="text-muted">{{ $review->created_at->format('F j, Y - g:i A') }}</small>
                                        </div>
                                        @if($review->user_id === Auth::id())
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-pastry">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete your review?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Review Form -->
                    @auth
                        @if(!Auth::user()->isAdmin())
                            @php
                                $hasPurchased = \App\Models\Order::where('user_id', Auth::id())
                                    ->whereHas('items', function($query) use ($product) {
                                        $query->where('product_id', $product->id);
                                    })
                                    ->exists();
                            @endphp
                            
                            @if($hasPurchased)
                                @php
                                    $userReview = $product->reviews()->where('user_id', Auth::id())->first();
                                @endphp
                                
                                <div class="review-form mt-4 p-3 rounded" style="background-color: var(--pastry-cream);">
                                    <h6 class="mb-3" style="color: var(--pastry-brown);">
                                        <i class="fas fa-pen me-2"></i>@if($userReview) Edit Your Review@else Write a Review@endif
                                    </h6>
                                    
                                    <form action="{{ $userReview ? route('reviews.update', $userReview->id) : route('reviews.store') }}" method="POST">
                                        @csrf
                                        @if($userReview)
                                            @method('PUT')
                                        @endif
                                        
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Rating</label>
                                                <div class="star-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ $userReview && $userReview->rating == $i ? 'checked' : '' }} required>
                                                        <label for="star{{ $i }}" class="star-label">
                                                            <i class="fas fa-star"></i>
                                                        </label>
                                                    @endfor>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Your Name</label>
                                                <input type="text" name="reviewer_name" value="{{ Auth::user()->name }}" class="form-control" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <label class="form-label">Review</label>
                                            <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience with this product..." required>{{ $userReview ? $userReview->comment : '' }}</textarea>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-pastry">
                                                <i class="fas fa-paper-plane me-2"></i>@if($userReview) Update Review@else Submit Review@endif
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>You need to purchase this product before you can write a review.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>Please <a href="{{ route('login') }}">log in</a> to write a review.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Please <a href="{{ route('login') }}">log in</a> to write a review.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function changeMainImage(imageSrc) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.src = imageSrc;
    }
}

// Star rating interaction
document.addEventListener('DOMContentLoaded', function() {
    const starInputs = document.querySelectorAll('input[name="rating"]');
    const starLabels = document.querySelectorAll('.star-label');
    
    starInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            starLabels.forEach((label, i) => {
                if (i <= index) {
                    label.style.color = '#ffc107';
                } else {
                    label.style.color = '#ddd';
                }
            });
        });
    });
    
    // Initialize star colors
    const checkedInput = document.querySelector('input[name="rating"]:checked');
    if (checkedInput) {
        const checkedIndex = Array.from(starInputs).indexOf(checkedInput);
        starLabels.forEach((label, i) => {
            if (i <= checkedIndex) {
                label.style.color = '#ffc107';
            } else {
                label.style.color = '#ddd';
            }
        });
    }
});
</script>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-label {
    cursor: pointer;
    color: #ddd;
    font-size: 1.5rem;
    margin-left: 0.25rem;
    transition: color 0.2s;
}

.star-label:hover {
    color: #ffc107 !important;
}

.img-thumbnail {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.img-thumbnail:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.review-item {
    background-color: #f8f9fa;
    border-radius: 8px;
}

.review-form {
    border: 1px solid var(--pastry-sand);
}
</style>
@endsection
