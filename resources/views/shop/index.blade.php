@extends('layouts.base')
@section('title', 'Shop')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Shop</p>
        <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Our pastries</h2>

        @if($products->isEmpty())
            <p class="text-muted">No products available at the moment.</p>
        @else
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card card-pastry h-100">
                            @if($product->thumbnail_photo)
                                <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 180px; color: var(--pastry-caramel);">
                                    <i class="fas fa-bread-slice fa-3x"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title font-serif" style="color: var(--pastry-brown);">{{ $product->name }}</h5>
                                <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 60) }}</p>
                                <p class="mb-2"><strong>{{ $product->formatted_price ?? '₱' . number_format($product->price, 2) }}</strong></p>
                                @auth
                                @if (!Auth::user()->isAdmin())
                                    <form action="{{ route('cart.store') }}" method="POST" class="mt-auto">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width: 4rem;">
                                            <button type="submit" class="btn btn-pastry">Add to cart</button>
                                        </div>
                                    </form>
                                @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-pastry btn-sm">Sign in to add to cart</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
