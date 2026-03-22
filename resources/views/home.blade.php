@extends('layouts.base')
@section('title', 'Home')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-lg-8 mx-auto text-center">
                <p class="text-uppercase tracking-wide mb-2" style="letter-spacing: 0.2em; color: var(--pastry-caramel); font-size: 0.9rem;">Pastry Shop</p>
                <h1 class="font-serif display-4 mb-3" style="color: var(--pastry-brown);">La Petite Pâtisserie</h1>
                <p class="lead mb-4" style="color: var(--pastry-text);">Handcrafted pastries &amp; sweet moments.</p>
                
                <!-- Home Page Search -->
                <div class="row mb-4">
                    <div class="col-lg-8 mx-auto">
                        <form method="GET" action="{{ route('shop.index') }}">
                            <div class="input-group input-group-lg">
                                <input type="text" name="search" class="form-control" placeholder="Search for pastries...">
                                <button type="submit" class="btn btn-pastry">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                @guest
                    <p class="mb-0">
                        <a href="{{ route('login') }}" class="btn btn-pastry btn-lg me-2">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-pastry btn-lg">Create Account</a>
                    </p>
                @else
                    <p class="mb-0">Hello, <strong>{{ auth()->user()->name }}</strong>. Welcome back.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-pastry mt-3">My Profile</a>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-pastry mt-3 ms-2">Browse Products</a>
                @endguest
            </div>
        </div>
    </div>
@endsection
