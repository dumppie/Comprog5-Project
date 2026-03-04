<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - La Petite Pâtisserie</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --pastry-cream: #FDF8F3;
            --pastry-sand: #E8DDD4;
            --pastry-caramel: #A67C52;
            --pastry-brown: #5C4033;
            --pastry-gold: #C9A227;
            --pastry-rose: #D4A5A5;
            --pastry-text: #3D2914;
        }
        
        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--pastry-cream);
            color: var(--pastry-text);
            min-height: 100vh;
        }
        
        .font-serif { 
            font-family: 'Cormorant Garamond', serif; 
        }
        
        h1, h2, h3, h4, h5, h6, .navbar-brand { 
            font-family: 'Cormorant Garamond', serif; 
            font-weight: 600; 
        }
        
        .navbar-admin {
            background: linear-gradient(135deg, var(--pastry-brown) 0%, var(--pastry-caramel) 100%);
            border-bottom: 3px solid var(--pastry-gold);
        }
        
        .navbar-admin .navbar-brand,
        .navbar-admin .nav-link {
            color: #fff !important;
        }
        
        .navbar-admin .nav-link:hover,
        .navbar-admin .nav-link.active {
            color: var(--pastry-gold) !important;
        }
        
        .navbar-admin .dropdown-menu {
            background: var(--pastry-cream);
            border: 1px solid var(--pastry-sand);
            box-shadow: 0 4px 20px rgba(92, 64, 51, 0.15);
        }
        
        .navbar-admin .dropdown-item {
            color: var(--pastry-text);
        }
        
        /* Push user menu to the right */
        .navbar-nav {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            align-items: center;
            justify-content: space-between;
        }
        
        .navbar-nav .nav-item {
            margin: 0 0.5rem;
        }
        
        .navbar-nav .nav-link {
            color: #fff !important;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--pastry-gold) !important;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .navbar-nav .dropdown-menu {
            background: var(--pastry-cream);
            border: 1px solid var(--pastry-sand);
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(92, 64, 51, 0.15);
        }
        
        .navbar-nav .dropdown-item {
            color: var(--pastry-text);
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .dropdown-item:hover {
            background: var(--pastry-sand);
            color: var(--pastry-brown);
        }
        
        /* Push user menu to the absolute right */
        .navbar-nav ul:last-child {
            margin-left: auto;
            margin-right: 0;
        }
        
        .btn-pastry {
            background-color: var(--pastry-caramel);
            border-color: var(--pastry-caramel);
            color: #fff;
            font-weight: 500;
        }
        
        .btn-pastry:hover {
            background-color: var(--pastry-brown);
            border-color: var(--pastry-brown);
            color: #fff;
        }
        
        .btn-outline-pastry {
            border-color: var(--pastry-caramel);
            color: var(--pastry-caramel);
            font-weight: 500;
        }
        
        .btn-outline-pastry:hover {
            background-color: var(--pastry-caramel);
            color: #fff;
        }
        
        .card-pastry {
            background: #fff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(92, 64, 51, 0.08);
        }
        
        .card {
            background: #fff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(92, 64, 51, 0.08);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--pastry-cream) 0%, #fff 100%);
            border-bottom: 1px solid var(--pastry-sand);
            border-radius: 12px 12px 0 0 !important;
        }
        
        .table thead th {
            background-color: var(--pastry-cream);
            color: var(--pastry-brown);
            font-weight: 600;
            border-bottom: 2px solid var(--pastry-sand);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(232, 221, 212, 0.3);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--pastry-caramel);
            box-shadow: 0 0 0 0.2rem rgba(166, 124, 82, 0.2);
        }
        
        .badge {
            font-weight: 500;
        }
        
        .btn-group .btn {
            border-radius: 6px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .alert-success {
            background-color: rgba(201, 162, 39, 0.1);
            color: var(--pastry-brown);
        }
        
        .alert-danger {
            background-color: rgba(212, 165, 165, 0.1);
            color: #8b3a3a;
        }
        
        .alert-warning {
            background-color: rgba(166, 124, 82, 0.1);
            color: var(--pastry-brown);
        }
        
        .alert-info {
            background-color: rgba(166, 124, 82, 0.1);
            color: var(--pastry-brown);
        }
        
        .toast {
            border-radius: 8px;
            border: none;
        }
        
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 30px rgba(92, 64, 51, 0.2);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--pastry-cream) 0%, #fff 100%);
            border-bottom: 1px solid var(--pastry-sand);
            border-radius: 12px 12px 0 0;
        }
        
        .page-header h1 {
            color: var(--pastry-brown);
        }
        
        .page-header p {
            color: var(--pastry-text);
            opacity: 0.8;
        }
        
        a {
            color: var(--pastry-caramel);
        }
        
        a:hover {
            color: var(--pastry-brown);
        }
        
        .stats-card {
            background: linear-gradient(135deg, var(--pastry-caramel) 0%, var(--pastry-brown) 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            transition: transform 0.2s ease-in-out;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-card.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .stats-card.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        
        .stats-card.bg-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-admin shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand font-serif d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-bread-slice"></i>
                <span>Admin Panel</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    
                    <!-- Product Management -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-box me-1"></i>Products
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                <i class="fas fa-list me-2"></i>All Products
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.products.create') }}">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.products.trash') }}">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="showImportModal()">
                                <i class="fas fa-file-excel me-2"></i>Import Excel
                            </a></li>
                        </ul>
                    </li>
                    
                    <!-- User Management -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users me-1"></i>Users
                        </a>
                    </li>
                    
                    <!-- Order Management -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i>Orders
                        </a>
                    </li>
                </ul>
                
                <!-- User Menu -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            {{ auth()->user()->name }}
                            @if(auth()->user()->isAdmin())
                                <span class="badge bg-warning text-dark">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i>Profile
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('home') }}">
                                <i class="fas fa-home me-2"></i>View Site
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid py-4">
        @include('layouts.flash-messages')
        
        <!-- Page Header -->
        <div class="row mb-4 page-header">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1">@yield('page-header', 'Dashboard')</h1>
                        <p class="mb-0">@yield('page-description', 'Welcome to admin panel')</p>
                    </div>
                    @yield('page-actions')
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-top mt-5 py-3">
        <div class="container-fluid text-center">
            <small style="color: var(--pastry-text); opacity: 0.7;">
                &copy; {{ date('Y') }} La Petite Pâtisserie. Admin Panel.
            </small>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    @stack('scripts')
    
    <!-- Flash Messages Auto Hide -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
