@extends('layouts.base')
@section('title', 'Sign In')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card card-pastry">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Welcome back</p>
                        <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Sign In</h2>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input id="password" type="password" name="password" class="form-control" required>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-pastry w-100">Sign In</button>
                        </form>
                        <p class="mt-4 mb-0 text-center"><a href="{{ route('register') }}">Don't have an account? Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
