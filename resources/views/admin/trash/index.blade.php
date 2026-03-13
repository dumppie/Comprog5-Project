@extends('layouts.base')
@section('title', 'Trash - Deleted Products')
@section('body')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="font-serif" style="color: var(--pastry-brown);">Trash - Deleted Products</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">← Back to Products</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($trashedProducts->count() === 0)
                <div class="text-center py-5">
                    <i class="fas fa-trash-alt fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No deleted products</h4>
                    <p class="text-muted">Products moved to trash will appear here.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
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
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <div class="me-3 bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; border-radius: 8px;">
                                                    <i class="fas fa-bread-slice text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->category }}</td>
                                    <td>₱{{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->deleted_at->format('M j, Y g:i A') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="restoreProduct({{ $product->id }})">
                                            <i class="fas fa-undo"></i> Restore
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="forceDeleteProduct({{ $product->id }})">
                                            <i class="fas fa-trash-alt"></i> Delete Forever
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function restoreProduct(id) {
    if (confirm('Are you sure you want to restore this product?')) {
        fetch(`/admin/products/${id}/restore`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product restored successfully!');
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
    if (confirm('Are you sure you want to permanently delete this product? This action cannot be undone!')) {
        fetch(`/admin/products/${id}/force`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product permanently deleted!');
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
@endsection
