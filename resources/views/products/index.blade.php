@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                        <button class="btn btn-success btn-sm" onclick="showImportModal()">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>
                        <a href="{{ route('admin.trash.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-trash"></i> Trash
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped" id="productsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thumbnail</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Photos</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
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
                                            <span class="badge {{ $product->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $product->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $product->photos->count() }}</span>
                                        </td>
                                        <td>{{ $product->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.products.show', $product->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($product->deleted_at)
                                                    <button class="btn btn-sm btn-success" 
                                                            onclick="restoreProduct({{ $product->id }})" 
                                                            title="Restore">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="forceDeleteProduct({{ $product->id }})" 
                                                            title="Permanently Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-warning" 
                                                            onclick="deleteProduct({{ $product->id }})" 
                                                            title="Move to Trash">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Products from Excel</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="importForm" action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excel_file">Excel File (.xlsx)</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" required>
                        <small class="form-text text-muted">
                            Required columns: name, category, description, price, stock_quantity<br>
                            Max file size: 10MB
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            responsive: true,
            order: [[0, 'desc']]
        });
    });

    function deleteProduct(id) {
        if (confirm('Are you sure you want to move this product to trash?')) {
            $.ajax({
                url: `{{ route('admin.products.destroy') }}`.replace('{product}', id),
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

    function showImportModal() {
        $('#importModal').modal('show');
    }

    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('Products imported successfully! ' + response.imported + ' products imported.');
                    if (response.errors && response.errors.length > 0) {
                        if (confirm('There were some errors. Download error report?')) {
                            window.open(response.error_report, '_blank');
                        }
                    }
                    $('#importModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred during import.');
            }
        });
    });
</script>
@endpush
