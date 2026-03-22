@extends('layouts.base')
@section('title', $product->name)

@section('body')
    @include('layouts.flash-messages')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Product</p>
                <h2 class="font-serif" style="color: var(--pastry-brown);">{{ $product->name }}</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">← Back to Shop</a>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                @if($product->photos->count() > 0)
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($product->photos as $i => $photo)
                                <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" class="d-block w-100 rounded" alt="{{ $photo->caption ?? $product->name }}" style="height: 420px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                @else
                    @if($product->thumbnail_photo)
                        <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="width: 100%; height: 420px; object-fit: cover;">
                    @else
                        <div class="p-5 rounded text-center" style="background-color: var(--pastry-sand);">
                            <i class="fas fa-image" style="font-size: 4rem; color: var(--pastry-caramel); opacity: 0.5;"></i>
                            <p class="mt-3 mb-0" style="color: var(--pastry-brown);">No image available</p>
                        </div>
                    @endif
                @endif

                @if($product->photos->count() > 1)
                    <div class="mt-3 d-flex gap-2">
                        @foreach($product->photos as $thumb)
                            <img src="{{ asset('storage/' . $thumb->photo_path) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" onclick="document.querySelector('#productCarousel .active img').src='{{ asset('storage/' . $thumb->photo_path) }}'">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-md-6">
                <div class="card p-3">
                    <div class="mb-3">
                        <h3 class="mb-1">{{ $product->formatted_price }}</h3>
                        <div class="small text-muted">Stock: <strong>{{ $product->stock_quantity }}</strong> — {{ $product->stock_status }}</div>
                    </div>

                    <p class="mb-4">{{ $product->description }}</p>

                    @auth
                        @if(!auth()->user()->isAdmin())
                            <form action="{{ route('cart.store') }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width: 5rem;">
                                    <button class="btn btn-pastry">Add to cart</button>
                                </div>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-pastry mb-3">Sign in to add to cart</a>
                    @endauth

                    <hr>

                    <div>
                        <h5>Reviews</h5>
                        <div class="mb-2">
                            <strong>{{ number_format($product->averageRating(), 1) }}</strong> / 5 — <small class="text-muted">({{ $product->ratingCount() }} reviews)</small>
                        </div>

                        @if($product->reviews->isEmpty())
                            <p class="text-muted">No reviews yet. Be the first to review!</p>
                        @else
                            <div class="list-group mb-3">
                                @foreach($product->reviews->sortByDesc('created_at') as $review)
                                    <div class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                                                        <div class="text-warning">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->rating)
                                                                    <i class="fas fa-star"></i>
                                                                @else
                                                                    <i class="far fa-star text-muted"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <div class="text-muted small">{{ $review->created_at->diffForHumans() }}</div>
                                                </div>
                                        @if($review->comment)
                                            <p class="mb-0 mt-2">{{ $review->comment }}</p>
                                        @endif
                                        @if(auth()->check() && $review->isAuthor(auth()->user()))
                                            <div class="mt-2">
                                                <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @auth
                            @if(!auth()->user()->isAdmin())
                                @if(auth()->user()->hasPurchasedProduct($product->id))
                                        @if(!auth()->user()->hasReviewedProduct($product->id))
                                        <form action="{{ route('reviews.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div class="mb-2">
                                                <label class="form-label">Your Rating</label>
                                                <select name="rating" class="form-select" required>
                                                    <option value="">Select rating</option>
                                                    @for($r=5;$r>=1;$r--)
                                                        <option value="{{ $r }}">{{ $r }} star{{ $r > 1 ? 's' : '' }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Comment (optional)</label>
                                                <textarea name="comment" class="form-control" rows="3" maxlength="1000" placeholder="Share your experience..."></textarea>
                                            </div>
                                            <button class="btn btn-pastry">Submit Review</button>
                                        </form>
                                    @else
                                        @php $myReview = auth()->user()->getReviewForProduct($product->id); @endphp
                                        <p class="text-muted">You have already reviewed this product. <a href="{{ route('reviews.edit', $myReview) }}">Edit your review</a></p>
                                    @endif
                                @else
                                    <p class="text-muted">Only customers who purchased this product can leave a review.</p>
                                @endif
                            @endif
                        @else
                            <p class="text-muted">Please <a href="{{ route('login') }}">sign in</a> to leave a review.</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
