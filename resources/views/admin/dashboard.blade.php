@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('page-header', 'Dashboard')
@section('page-description', 'System overview and quick statistics')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="color: var(--pastry-text); opacity: 0.75;">Total Products</h6>
                        <h2 class="mb-0" style="color: var(--pastry-brown);">{{ App\Models\Product::count() }}</h2>
                    </div>
                    <div style="color: var(--pastry-caramel); opacity: 0.5;">
                        <i class="fas fa-box fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none d-flex align-items-center" style="color: var(--pastry-brown);">
                    View Products <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="color: var(--pastry-text); opacity: 0.75;">In Stock</h6>
                        <h2 class="mb-0" style="color: var(--pastry-brown);">{{ App\Models\Product::where('stock_quantity', '>', 0)->count() }}</h2>
                    </div>
                    <div style="color: var(--pastry-caramel); opacity: 0.5;">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('admin.products.index') }}?filter=in_stock" class="text-decoration-none d-flex align-items-center" style="color: var(--pastry-brown);">
                    View In Stock <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="color: var(--pastry-text); opacity: 0.75;">Total Users</h6>
                        <h2 class="mb-0" style="color: var(--pastry-brown);">{{ App\Models\User::count() }}</h2>
                    </div>
                    <div style="color: var(--pastry-caramel); opacity: 0.5;">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none d-flex align-items-center" style="color: var(--pastry-brown);">
                    Manage Users <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2" style="color: var(--pastry-text); opacity: 0.75;">Total Orders</h6>
                        <h2 class="mb-0" style="color: var(--pastry-brown);">{{ App\Models\Order::count() }}</h2>
                    </div>
                    <div style="color: var(--pastry-caramel); opacity: 0.5;">
                        <i class="fas fa-shopping-cart fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none d-flex align-items-center" style="color: var(--pastry-brown);">
                    View Orders <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 font-serif">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-pastry w-100">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-pastry w-100" onclick="showImportModal()">
                            <i class="fas fa-file-excel me-2"></i>Import Products
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-pastry w-100">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-pastry w-100">
                            <i class="fas fa-shopping-cart me-2"></i>View Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Low Stock Alerts -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 font-serif">
                    <i class="fas fa-clock me-2"></i>Recent Orders
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->orderItems->count() }} items</td>
                                    <td class="fw-bold">${{ number_format($order->orderItems->sum(function($item) { return $item->unit_price * $item->quantity; }), 2) }}</td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No recent orders</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 font-serif">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alerts
                </h5>
            </div>
            <div class="card-body">
                @forelse($lowStockProducts as $product)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <div class="fw-bold">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->category }}</small>
                        </div>
                        <span class="badge bg-warning">Only {{ $product->stock_quantity }} left</span>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-check-circle fa-3x mb-3" style="color: var(--pastry-caramel);"></i>
                        <p class="font-serif">All products have sufficient stock</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Top Selling Products -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 font-serif">
                    <i class="fas fa-trophy me-2"></i>Top Selling Products
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($topProducts as $index => $product)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="d-flex align-items-center p-3 border rounded" style="border-color: var(--pastry-sand);">
                                <div class="me-3">
                                    <div class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->total_sold }} sold</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">
                            <i class="fas fa-chart-line fa-3x mb-3"></i>
                            <p>No sales data available yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Import Instructions</h6>
                    <small>Upload an Excel file (.xlsx or .xls) with columns: name, category, description, price, stock_quantity</small>
                </div>
                <form id="importForm" action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="excel_file" class="form-label">Select Excel File</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" 
                               accept=".xlsx,.xls" required>
                        @error('excel_file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="importForm" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Import
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showImportModal() {
        $('#importModal').modal('show');
    }

    $(document).ready(function() {
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var submitBtn = $('button[form="importForm"]');
            var originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#importModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Import Successful!',
                            html: `Successfully imported ${response.imported} products.`,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // Reload to show updated stats
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Import Failed',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'An error occurred during import.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                    $('#importForm')[0].reset(); // Reset form
                }
            });
        });
    });
</script>
@endpush
