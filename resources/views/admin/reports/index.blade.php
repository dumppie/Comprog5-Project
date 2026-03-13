@extends('layouts.base')
@section('title', 'Reports & Analytics')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-serif" style="color: var(--pastry-brown);">Reports & Analytics</h2>
        </div>

        <!-- Report Navigation -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-bar fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Sales Reports</h5>
                        <p class="card-text text-muted">Analyze your sales performance</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.reports.yearly-sales') }}" class="btn btn-outline-primary btn-sm">Yearly Sales</a>
                            <a href="{{ route('admin.reports.monthly-sales') }}" class="btn btn-outline-primary btn-sm">Monthly Sales</a>
                            <a href="{{ route('admin.reports.date-range-sales') }}" class="btn btn-outline-primary btn-sm">Date Range</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-pie fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Product Analytics</h5>
                        <p class="card-text text-muted">Product performance insights</p>
                        <div class="d-grid">
                            <a href="{{ route('admin.reports.product-sales') }}" class="btn btn-outline-success">Product Distribution</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-tachometer-alt fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Quick Stats</h5>
                        <p class="card-text text-muted">Key business metrics</p>
                        <div class="d-grid">
                            <a href="{{ route('admin.reports.dashboard') }}" class="btn btn-outline-info">View Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Access</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Sales Analysis</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin.reports.yearly-sales') }}" class="text-decoration-none">📊 Year-over-year comparison</a></li>
                            <li><a href="{{ route('admin.reports.monthly-sales') }}" class="text-decoration-none">📈 Monthly trends</a></li>
                            <li><a href="{{ route('admin.reports.date-range-sales') }}" class="text-decoration-none">📅 Custom date analysis</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Product Insights</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin.reports.product-sales') }}" class="text-decoration-none">🥧 Sales by product</a></li>
                            <li><a href="{{ route('admin.products.index') }}" class="text-decoration-none">📦 Product management</a></li>
                            <li><a href="{{ route('admin.orders.index') }}" class="text-decoration-none">🛒 Order details</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
