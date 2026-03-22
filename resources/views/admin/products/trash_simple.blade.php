@extends('layouts.admin')

@section('title', 'Product Trash')

@section('page-header', 'Product Trash')
@section('page-description', 'Manage deleted products')

@section('page-actions')
<a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i>Back to Products
</a>
@endsection

@section('content')
<div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
    <div class="card-header bg-white border-0" style="border-bottom: 2px solid var(--pastry-sand) !important;">
        <h5 class="mb-0" style="color: var(--pastry-brown);">
            <i class="fas fa-trash me-2"></i>Deleted Products ({{ $trashedProducts->count() }})
        </h5>
    </div>
    <div class="card-body">
        @if($trashedProducts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--pastry-sand);">
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashedProducts as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->thumbnail_photo)
                                            <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="me-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 2px solid var(--pastry-sand);">
                                        @else
                                            <div class="me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px; background-color: var(--pastry-sand); border-radius: 8px;">
                                                <i class="fas fa-image" style="color: var(--pastry-caramel);"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            @if($product->description)
                                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: var(--pastry-sand); color: var(--pastry-brown);">
                                        {{ ucfirst($product->category) }}
                                    </span>
                                </td>
                                <td class="fw-bold">₱{{ number_format($product->price, 2) }}</td>
                                <td>
                                    <small>{{ $product->deleted_at->format('M d, Y H:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="restoreProduct({{ $product->id }})" 
                                                title="Restore Product">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="forceDeleteProduct({{ $product->id }})" 
                                                title="Permanently Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div style="color: var(--pastry-caramel); opacity: 0.5; font-size: 4rem;">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h4 class="mt-3" style="color: var(--pastry-brown);">No Deleted Products</h4>
                <p class="text-muted">The trash is empty. Products you delete will appear here.</p>
                <a href="{{ route('admin.products.index') }}" class="btn btn-pastry mt-3">
                    <i class="fas fa-box me-2"></i>View Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function restoreProduct(id) {
    if (confirm('Are you sure you want to restore this product?')) {
        fetch('/admin/products/' + id + '/restore', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred while restoring the product.');
        });
    }
}

function forceDeleteProduct(id) {
    if (confirm('Are you sure you want to permanently delete this product? This action cannot be undone.')) {
        fetch('/admin/products/' + id + '/force', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred while deleting the product.');
        });
    }
}
</script>
@endpush
