@extends('layouts.admin')

@section('title', 'Product Details')

@section('page-header', 'Product Details')
@section('page-description', 'View detailed information about this product')

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-pastry">
        <i class="fas fa-edit me-2"></i>Edit Product
    </a>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-pastry">
        <i class="fas fa-arrow-left me-2"></i>Back to Products
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Product Image Section -->
    <div class="col-md-4">
        <div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
            <div class="card-header bg-white border-0" style="border-bottom: 2px solid var(--pastry-sand) !important;">
                <h6 class="mb-0" style="color: var(--pastry-brown);">
                    <i class="fas fa-image me-2"></i>Product Image
                </h6>
            </div>
            <div class="card-body text-center">
                @if($product->thumbnail_photo)
                    <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" 
                         class="img-fluid rounded" 
                         style="width: 100%; height: 300px; object-fit: cover; border: 2px solid var(--pastry-sand); border-radius: 12px;"
                         alt="{{ $product->name }}">
                @else
                    <div class="p-5 rounded text-center" style="background-color: var(--pastry-sand);">
                        <i class="fas fa-image" style="font-size: 4rem; color: var(--pastry-caramel); opacity: 0.5;"></i>
                        <p class="mt-3 mb-0" style="color: var(--pastry-brown);">No thumbnail available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="col-md-8">
        <div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
            <div class="card-header bg-white border-0" style="border-bottom: 2px solid var(--pastry-sand) !important;">
                <h6 class="mb-0" style="color: var(--pastry-brown);">
                    <i class="fas fa-info-circle me-2"></i>Product Information
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150" style="color: var(--pastry-brown); font-weight: 600;">Product ID:</td>
                            <td><span class="badge bg-secondary">#{{ $product->id }}</span></td>
                        </tr>
                        <tr>
                            <td style="color: var(--pastry-brown); font-weight: 600;">Name:</td>
                            <td class="fw-semibold" style="color: var(--pastry-brown);">{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td style="color: var(--pastry-brown); font-weight: 600;">Category:</td>
                            <td>
                                <span class="badge" style="background-color: var(--pastry-sand); color: var(--pastry-brown);">
                                    {{ ucfirst($product->category) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: var(--pastry-brown); font-weight: 600;">Price:</td>
                            <td class="fw-bold" style="color: var(--pastry-brown); font-size: 1.2rem;">{{ $product->formatted_price }}</td>
                        </tr>
                        <tr>
                            <td style="color: var(--pastry-brown); font-weight: 600;">Stock Quantity:</td>
                            <td>
                                <span class="badge {{ $product->stock_quantity > 10 ? 'bg-success' : ($product->stock_quantity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $product->stock_quantity }} units
                                </span>
                                <small class="text-muted ms-2">({{ $product->stock_status }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: var(--pastry-brown); font-weight: 600;">Status:</td>
                            <td>
                                <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: var(--pastry-brown); font-weight: 600;">Created:</td>
                            <td>{{ $product->created_at->format('F j, Y - g:i A') }}</td>
                        </tr>
                        <tr>
                            <td style="color: var(--pastry-brown); font-weight: 600;">Last Updated:</td>
                            <td>{{ $product->updated_at->format('F j, Y - g:i A') }}</td>
                        </tr>
                        @if($product->deleted_at)
                            <tr>
                                <td style="color: var(--pastry-brown); font-weight: 600;">Deleted At:</td>
                                <td><span class="text-danger">{{ $product->deleted_at->format('F j, Y - g:i A') }}</span></td>
                            </tr>
                        @endif
                    </table>
                </div>

                @if($product->description)
                    <div class="mt-4">
                        <h6 style="color: var(--pastry-brown);">
                            <i class="fas fa-align-left me-2"></i>Description
                        </h6>
                        <div class="p-3 rounded" style="background-color: var(--pastry-cream);">
                            <p class="mb-0">{{ $product->description }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Product Photos Section -->
@if($product->photos->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
                <div class="card-header bg-white border-0" style="border-bottom: 2px solid var(--pastry-sand) !important;">
                    <h6 class="mb-0" style="color: var(--pastry-brown);">
                        <i class="fas fa-images me-2"></i>Product Photos ({{ $product->photos->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($product->photos as $photo)
                            <div class="col-md-3 mb-4">
                                <div class="card border-2 shadow" style="border-color: var(--pastry-sand);">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                         class="card-img-top" 
                                         style="width: 100%; height: 200px; object-fit: cover;"
                                         alt="{{ $photo->caption ?? $product->name }}">
                                    <div class="card-body p-3">
                                        @if($photo->caption)
                                            <small class="text-muted">Caption:</small>
                                            <p class="card-text mb-2">{{ $photo->caption }}</p>
                                        @endif
                                        @if($photo->is_thumbnail)
                                            <span class="badge" style="background-color: var(--pastry-caramel); color: white;">
                                                <i class="fas fa-star me-1"></i>Thumbnail
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
