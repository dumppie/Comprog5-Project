<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                    {{ Auth::check() ? Auth::user()->name : 'Account' }}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a>
                        <div class="dropdown-divider"></div>
                    @endif
                    @if (Auth::check())
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                        <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</nav>
