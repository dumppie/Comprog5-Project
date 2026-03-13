@extends('layouts.base')
@section('title', 'Reports & Analytics')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-serif" style="color: var(--pastry-brown);">Reports & Analytics</h2>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">₱{{ number_format($todaySales, 2) }}</h5>
                        <p class="card-text text-muted">Today's Sales</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-success">₱{{ number_format($monthSales, 2) }}</h5>
                        <p class="card-text text-muted">This Month</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-info">₱{{ number_format($yearSales, 2) }}</h5>
                        <p class="card-text text-muted">This Year</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-warning">{{ $totalOrders }}</h5>
                        <p class="card-text text-muted">Total Orders</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Navigation -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Sales Reports</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.reports.yearly-sales') }}" class="btn btn-outline-primary">📊 Yearly Sales</a>
                            <a href="{{ route('admin.reports.monthly-sales') }}" class="btn btn-outline-primary">📈 Monthly Sales</a>
                            <a href="{{ route('admin.reports.date-range-sales') }}" class="btn btn-outline-primary">📅 Date Range Sales</a>
                            <a href="{{ route('admin.reports.product-sales') }}" class="btn btn-outline-primary">🥧 Product Sales Distribution</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Top Selling Products</h5>
                        @if($topProducts->isEmpty())
                            <p class="text-muted">No sales data available.</p>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($topProducts as $index => $product)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $index + 1 }}. {{ $product->name }}</span>
                                        <span class="badge bg-primary rounded-pill">{{ $product->total_sold }} sold</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders Alert -->
        @if($pendingOrders > 0)
            <div class="alert alert-warning mt-4">
                <strong>⚠️ Attention:</strong> You have {{ $pendingOrders }} pending orders that need processing.
                <a href="{{ route('admin.orders.index') }}" class="alert-link">View Orders</a>
            </div>
        @endif
    </div>
@endsection
