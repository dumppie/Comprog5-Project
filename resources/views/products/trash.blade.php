@extends('layouts.app')

@section('title', 'Trash - Deleted Products')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Trash - Deleted Products</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($trashedProducts->count() === 0)
                        <div class="text-center py-5">
                            <i class="fas fa-trash fa-3x text-muted"></i>
                            <h4 class="text-muted mt-3">No products in trash</h4>
                            <p class="text-muted">Products that you delete will appear here. You can restore them or permanently delete them.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped" id="trashTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Thumbnail</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trashedProducts as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                @if($product->thumbnail_photo)
                                                    <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td><span class="badge badge-info">{{ $product->category }}</span></td>
                                            <td>{{ $product->formatted_price }}</td>
                                            <td>
                                                <span class="badge {{ $product->stock_quantity > 0 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-danger">{{ $product->deleted_at->format('M d, Y') }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-success" 
                                                            onclick="restoreProduct({{ $product->id }})" 
                                                            title="Restore Product">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="forceDeleteProduct({{ $product->id }})" 
                                                            title="Permanently Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#trashTable').DataTable({
            responsive: true,
            order: [[6, 'desc']] // Sort by deleted_at descending
        });
    });

    function restoreProduct(id) {
        if (confirm('Are you sure you want to restore this product?')) {
            $.ajax({
                url: `{{ route('admin.products.restore') }}`.replace('{product}', id),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while restoring the product.');
                }
            });
        }
    }

    function forceDeleteProduct(id) {
        if (confirm('Are you sure you want to permanently delete this product? This action cannot be undone!')) {
            $.ajax({
                url: `{{ route('admin.products.force-delete') }}`.replace('{product}', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the product.');
                }
            });
        }
    }
</script>
@endpush
