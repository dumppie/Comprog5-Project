@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Details: {{ $product->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                @if($product->thumbnail_photo)
                                    <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" 
                                         class="img-fluid rounded" 
                                         style="max-width: 100%; height: 300px; object-fit: cover;"
                                         alt="{{ $product->name }}">
                                @else
                                    <div class="bg-light p-5 rounded text-center">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <p class="mt-2 text-muted">No thumbnail</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 150px;">Product ID:</th>
                                    <td>{{ $product->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td><span class="badge badge-info">{{ ucfirst($product->category) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td><strong>{{ $product->formatted_price }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Stock Quantity:</th>
                                    <td>
                                        <span class="badge {{ $product->stock_quantity > 0 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $product->stock_quantity }} units
                                        </span>
                                        <small class="text-muted">({{ $product->stock_status }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge {{ $product->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $product->created_at->format('F j, Y - g:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $product->updated_at->format('F j, Y - g:i A') }}</td>
                                </tr>
                                @if($product->deleted_at)
                                    <tr>
                                        <th>Deleted At:</th>
                                        <td><span class="text-danger">{{ $product->deleted_at->format('F j, Y - g:i A') }}</span></td>
                                    </tr>
                                @endif
                            </table>

                            @if($product->description)
                                <h5 class="mt-4">Description</h5>
                                <p>{{ $product->description }}</p>
                            @endif
                        </div>
                    </div>

                    @if($product->photos->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Product Photos ({{ $product->photos->count() }})</h5>
                                <div class="row">
                                    @foreach($product->photos as $photo)
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                                     class="card-img-top" 
                                                     style="width: 100%; height: 200px; object-fit: cover;"
                                                     alt="{{ $photo->caption ?? $product->name }}">
                                                <div class="card-body p-2">
                                                    @if($photo->caption)
                                                        <small class="text-muted">Caption:</small>
                                                        <p class="card-text">{{ $photo->caption }}</p>
                                                    @endif
                                                    @if($photo->is_thumbnail)
                                                        <span class="badge badge-primary">Thumbnail</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
