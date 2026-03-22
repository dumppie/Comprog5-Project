@extends('layouts.admin')

@section('title', 'Product Trash')

@section('page-header', 'Product Trash')
@section('page-description', 'Manage deleted products - restore or permanently delete')

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Products
    </a>
    <button class="btn btn-outline-danger" onclick="emptyTrash()" id="emptyTrashBtn">
        <i class="fas fa-trash-alt me-2"></i>Empty Trash
    </button>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0" style="background-color: #D4EDDA; border-left: 4px solid #28A745 !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0" style="background-color: #F8D7DA; border-left: 4px solid #DC3545 !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

<div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
    <div class="card-header bg-white border-0" style="border-bottom: 2px solid var(--pastry-sand) !important;">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="color: var(--pastry-brown);">
                <i class="fas fa-trash me-2"></i>Deleted Products ({{ $trashedProducts->count() }})
            </h5>
            @if($trashedProducts->count() > 0)
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Products will be permanently deleted after 30 days
                </small>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($trashedProducts->count() > 0)
            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text" style="border-color: var(--pastry-sand); background-color: var(--pastry-cream);">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control border-2" id="searchInput" 
                               placeholder="Search trashed products..." style="border-color: var(--pastry-sand);">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-control border-2" id="categoryFilter" style="border-color: var(--pastry-sand);">
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
                    <select class="form-control border-2" id="dateFilter" style="border-color: var(--pastry-sand);">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="older">Older</option>
                    </select>
                </div>
            </div>

            <!-- Products Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="trashTable">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--pastry-sand);">
                            <th width="50">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Deleted At</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashedProducts as $product)
                            <tr class="product-row" data-category="{{ $product->category }}" data-date="{{ $product->deleted_at->format('Y-m-d') }}">
                                <td>
                                    <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                                </td>
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
                                <td class="fw-bold">{{ $product->formatted_price }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted">{{ $product->deleted_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $product->deleted_at->format('H:i A') }}</small>
                                    </div>
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

            <!-- Bulk Actions -->
            @if($trashedProducts->count() > 0)
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button class="btn btn-outline-success" onclick="bulkRestore()" id="bulkRestoreBtn" style="display: none;">
                                <i class="fas fa-undo me-2"></i>Restore Selected
                            </button>
                            <button class="btn btn-outline-danger" onclick="bulkForceDelete()" id="bulkDeleteBtn" style="display: none;">
                                <i class="fas fa-trash me-2"></i>Delete Permanently
                            </button>
                        </div>
                        <small class="text-muted">
                            <span id="selectedCount">0</span> products selected
                        </small>
                    </div>
                </div>
            @endif
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
    $(document).ready(function() {
        // Search functionality
        $('#searchInput').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('.product-row').each(function() {
                var productName = $(this).find('td:nth-child(2)').text().toLowerCase();
                $(this).toggle(productName.includes(searchTerm));
            });
        });

        // Category filter
        $('#categoryFilter').on('change', function() {
            var category = $(this).val();
            $('.product-row').each(function() {
                if (category === '' || $(this).data('category') === category) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Date filter
        $('#dateFilter').on('change', function() {
            var filter = $(this).val();
            var today = new Date();
            
            $('.product-row').each(function() {
                var productDate = new Date($(this).data('date'));
                var show = false;
                
                switch(filter) {
                    case '':
                        show = true;
                        break;
                    case 'today':
                        show = productDate.toDateString() === today.toDateString();
                        break;
                    case 'week':
                        var weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                        show = productDate >= weekAgo;
                        break;
                    case 'month':
                        var monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                        show = productDate >= monthAgo;
                        break;
                    case 'older':
                        var monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                        show = productDate < monthAgo;
                        break;
                }
                
                $(this).toggle(show);
            });
        });

        // Select all functionality
        $('#selectAll').on('change', function() {
            $('.product-checkbox').prop('checked', $(this).is(':checked'));
            updateBulkButtons();
        });

        // Individual checkbox selection
        $('.product-checkbox').on('change', function() {
            updateBulkButtons();
        });

        function updateBulkButtons() {
            var selectedCount = $('.product-checkbox:checked').length;
            var totalCount = $('.product-checkbox').length;
            
            $('#selectAll').prop('checked', selectedCount === totalCount && totalCount > 0);
            $('#selectedCount').text(selectedCount);
            
            if (selectedCount > 0) {
                $('#bulkRestoreBtn, #bulkDeleteBtn').show();
            } else {
                $('#bulkRestoreBtn, #bulkDeleteBtn').hide();
            }
        }
    });

    function restoreProduct(id) {
        if (confirm('Are you sure you want to restore this product?')) {
            $.ajax({
                url: '/admin/products/' + id + '/restore',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
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
        if (confirm('Are you sure you want to permanently delete this product? This action cannot be undone.')) {
            $.ajax({
                url: '/admin/products/' + id + '/force',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
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

    function bulkRestore() {
        var selectedIds = $('.product-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            showToast('No products selected', 'error');
            return;
        }

        if (confirm('Are you sure you want to restore ' + selectedIds.length + ' product(s)?')) {
            $.ajax({
                url: '/admin/products/bulk-restore',
                method: 'POST',
                data: {
                    product_ids: selectedIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        showToast('Error: ' + response.message, 'error');
                    }
                },
                error: function() {
                    showToast('An error occurred while restoring products.', 'error');
                }
            });
        }
    }

    function bulkForceDelete() {
        var selectedIds = $('.product-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            showToast('No products selected', 'error');
            return;
        }

        if (confirm('Are you sure you want to permanently delete ' + selectedIds.length + ' product(s)? This action cannot be undone.')) {
            $.ajax({
                url: '/admin/products/bulk-force-delete',
                method: 'DELETE',
                data: {
                    product_ids: selectedIds,
                    _token: '{{ csrf_token() }}'
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

    function emptyTrash() {
        if (confirm('Are you sure you want to empty the trash? This will permanently delete all products in trash. This action cannot be undone.')) {
            $.ajax({
                url: '/admin/products/empty-trash',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        showToast('Error: ' + response.message, 'error');
                    }
                },
                error: function() {
                    showToast('An error occurred while emptying trash.', 'error');
                }
            });
        }
    }

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
        
        var toastElement = $(toastHtml);
        $('.toast-container').append(toastElement);
        toastElement.toast({ delay: 3000 }).toast('show');
        
        setTimeout(function() {
            toastElement.remove();
        }, 4000);
    }
</script>
@endpush
