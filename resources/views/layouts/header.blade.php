<nav class="navbar navbar-expand-lg navbar-light py-3" style="background: linear-gradient(135deg, #fff 0%, var(--pastry-cream) 100%); border-bottom: 1px solid var(--pastry-sand);">
    <div class="container">
        <a class="navbar-brand font-serif d-flex align-items-center gap-2" href="{{ route('home') }}" style="font-size: 1.5rem; color: var(--pastry-brown);">
            <i class="fas fa-bread-slice" style="color: var(--pastry-caramel);"></i>
            La Petite Pâtisserie
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="border-color: var(--pastry-caramel);">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    @auth
                        @if (Auth::user()->isAdmin())
                            <a class="nav-link" href="{{ route('admin.dashboard') }}" style="color: var(--pastry-text); font-weight: 500;">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        @else
                            <a class="nav-link" href="{{ route('home') }}" style="color: var(--pastry-text); font-weight: 500;">Home</a>
                        @endif
                    @endauth
                    @guest
                        <a class="nav-link" href="{{ route('home') }}" style="color: var(--pastry-text); font-weight: 500;">Home</a>
                    @endguest
                </li>
                <li class="nav-item">
                    @auth
                        @if (Auth::user()->isAdmin())
                            <a class="nav-link" href="{{ route('admin.products.index') }}" style="color: var(--pastry-text); font-weight: 500;">
                                <i class="fas fa-box me-1"></i>Product Management
                            </a>
                        @else
                            <a class="nav-link" href="{{ route('shop.index') }}" style="color: var(--pastry-text); font-weight: 500;">Shop</a>
                        @endif
                    @endauth
                    @guest
                        <a class="nav-link" href="{{ route('shop.index') }}" style="color: var(--pastry-text); font-weight: 500;">Shop</a>
                    @endguest
                </li>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                @auth
                @if (!Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.index') }}" style="color: var(--pastry-text); font-weight: 500;"><i class="fas fa-shopping-cart me-1"></i>Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.index') }}" style="color: var(--pastry-text); font-weight: 500;">My Orders</a>
                </li>
                @endif
                @endauth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: var(--pastry-text); font-weight: 500;">
                        <i class="fas fa-user-circle" style="color: var(--pastry-caramel);"></i>
                        {{ Auth::check() ? Auth::user()->name : 'Account' }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-sm" style="border: 1px solid var(--pastry-sand); border-radius: 8px;">
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-users me-2"></i>Users</a>
                            <a class="dropdown-item" href="{{ route('admin.orders.index') }}"><i class="fas fa-receipt me-2"></i>Orders</a>
                            <hr class="dropdown-divider">
                        @endif
                        @if (Auth::check())
                            <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Profile</a>
                            <hr class="dropdown-divider">
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        @else
                            <a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i>Register</a>
                            <a class="dropdown-item" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i>Login</a>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
