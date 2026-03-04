@extends('layouts.admin')

@section('title', 'Products Management')

@section('page-header', 'Products Management')
@section('page-description', 'Manage your product inventory')

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.products.create') }}" class="btn btn-pastry">
        <i class="fas fa-plus me-2"></i>Add Product
    </a>
    <button class="btn btn-pastry" onclick="showImportModal()">
        <i class="fas fa-file-excel me-2"></i>Import Excel
    </button>
    <button class="btn btn-outline-pastry" onclick="bulkDelete()" id="bulkDeleteBtn" style="display: none;">
        <i class="fas fa-trash me-2"></i>Delete Selected
    </button>
    <a href="{{ route('admin.products.trash') }}" class="btn btn-outline-pastry">
        <i class="fas fa-trash me-2"></i>Trash
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Search and Filter -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="food">Food</option>
                    <option value="books">Books</option>
                    <option value="toys">Toys</option>
                    <option value="sports">Sports</option>
                    <option value="home">Home</option>
                    <option value="beauty">Beauty</option>
                    <option value="automotive">Automotive</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table table-hover" id="productsTable">
                <thead class="table-light">
                    <tr>
                        <th>
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>Product</th>
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
                            <td>
                                <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail->photo_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge" style="background-color: var(--pastry-sand); color: var(--pastry-brown);">{{ $product->category }}</span></td>
                            <td class="fw-bold">{{ $product->formatted_price }}</td>
                            <td>
                                <span class="badge {{ $product->stock_quantity > 10 ? 'bg-success' : ($product->stock_quantity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" type="checkbox" 
                                           data-product-id="{{ $product->id }}" 
                                           {{ $product->status === 'active' ? 'checked' : '' }}>
                                    <label class="form-check-label"></label>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $product->photos->count() }}</span>
                            </td>
                            <td>
                                <small>{{ $product->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.show', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($product->deleted_at)
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="restoreProduct({{ $product->id }})" 
                                                title="Restore">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="forceDeleteProduct({{ $product->id }})" 
                                                title="Permanently Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-danger" 
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-excel me-2"></i>Import Products from Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="importForm" action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Excel File (.xlsx)</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" required>
                        <div class="form-text">
                            Required columns: name, category, description, price, stock_quantity<br>
                            Max file size: 10MB
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#productsTable').DataTable({
        responsive: true,
        order: [[1, 'asc']],
        pageLength: 25,
        language: {
            search: "Search products:",
            lengthMenu: "Show _MENU_ products"
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Category filter
    $('#categoryFilter').on('change', function() {
        const value = $(this).val();
        if (value) {
            table.column(2).search(value).draw();
        } else {
            table.column(2).search('').draw();
        }
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        const value = $(this).val();
        if (value) {
            table.column(5).search(value).draw();
        } else {
            table.column(5).search('').draw();
        }
    });

    // Select all functionality
    $('#selectAll').on('change', function() {
        $('.product-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkDeleteButton();
    });

    // Update bulk delete button visibility
    $('.product-checkbox').on('change', function() {
        updateBulkDeleteButton();
    });

    function updateBulkDeleteButton() {
        const checkedCount = $('.product-checkbox:checked').length;
        if (checkedCount > 0) {
            $('#bulkDeleteBtn').show().text(`Delete Selected (${checkedCount})`);
        } else {
            $('#bulkDeleteBtn').hide();
        }
    }

    // Bulk delete function
    function bulkDelete() {
        const selectedIds = [];
        $('.product-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            showToast('No products selected', 'error');
            return;
        }

        if (confirm(`Are you sure you want to delete ${selectedIds.length} product(s)?`)) {
            $.ajax({
                url: '/admin/products/bulk-delete',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_ids: selectedIds
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        showToast('Error: ' + response.message, 'error');
                    }
                },
                error: function() {
                    showToast('An error occurred while deleting products.', 'error');
                }
            });
        }
    }

    // Status toggle
    $('.status-toggle').on('change', function() {
        const productId = $(this).data('product-id');
        const status = $(this).prop('checked') ? 'active' : 'inactive';
        
        $.ajax({
            url: `/admin/products/${productId}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                // Show success message
                showToast('Product status updated successfully', 'success');
            },
            error: function() {
                // Revert toggle on error
                $(this).prop('checked', !$(this).prop('checked'));
                showToast('Error updating product status', 'error');
            }
        });
    });
});

function deleteProduct(id) {
    if (confirm('Are you sure you want to move this product to trash?')) {
        $.ajax({
            url: `/admin/products/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    showToast('Error: ' + response.message, 'error');
                }
            },
            error: function() {
                showToast('An error occurred while deleting the product.', 'error');
            }
        });
    }
}

function restoreProduct(id) {
    if (confirm('Are you sure you want to restore this product?')) {
        $.ajax({
            url: `/admin/products/${id}/restore`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    showToast('Error: ' + response.message, 'error');
                }
            },
            error: function() {
                showToast('An error occurred while restoring the product.', 'error');
            }
        });
    }
}

function forceDeleteProduct(id) {
    if (confirm('Are you sure you want to permanently delete this product? This action cannot be undone!')) {
        $.ajax({
            url: `/admin/products/${id}/force`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    showToast('Error: ' + response.message, 'error');
                }
            },
            error: function() {
                showToast('An error occurred while deleting the product.', 'error');
            }
        });
    }
}

function showImportModal() {
    const modal = new bootstrap.Modal(document.getElementById('importModal'));
    modal.show();
}

$('#importForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showToast('Products imported successfully! ' + response.imported + ' products imported.', 'success');
                if (response.errors && response.errors.length > 0) {
                    if (confirm('There were some errors. Download error report?')) {
                        window.open(response.error_report, '_blank');
                    }
                }
                bootstrap.Modal.getInstance(document.getElementById('importModal')).hide();
                location.reload();
            } else {
                showToast('Error: ' + response.message, 'error');
            }
        },
        error: function() {
            showToast('An error occurred during import.', 'error');
        }
    });
});

function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    const toastElement = $(toastHtml);
    $('.toast-container').append(toastElement);
    const toast = new bootstrap.Toast(toastElement[0]);
    toast.show();
    
    toastElement.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
@endpush
