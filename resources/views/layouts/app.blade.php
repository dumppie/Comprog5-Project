<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') - {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; margin: 0; padding: 1rem; background: #f5f5f5; }
        .container { max-width: 960px; margin: 0 auto; }
        nav { background: #333; color: #fff; padding: 0.75rem 1rem; margin-bottom: 1.5rem; border-radius: 6px; }
        nav a { color: #fff; text-decoration: none; margin-right: 1rem; }
        nav a:hover { text-decoration: underline; }
        .card { background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1rem; }
        .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error, .alert-danger { background: #f8d7da; color: #721c24; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.25rem; font-weight: 500; }
        input[type="text"], input[type="email"], input[type="password"], textarea { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
        button, .btn { padding: 0.5rem 1rem; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; display: inline-block; font-size: 1rem; }
        .btn-primary { background: #0d6efd; color: #fff; }
        .btn-primary:hover { background: #0b5ed7; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .errors { color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }
        .pagination { margin-top: 1rem; }
        .pagination a, .pagination span { padding: 0.25rem 0.5rem; margin-right: 0.25rem; }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="{{ route('home') }}">Home</a>
            @auth
                <a href="{{ route('profile.edit') }}">Profile</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}">Admin – Users</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="padding:0.25rem 0.5rem;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </nav>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0; padding-left:1.25rem;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
