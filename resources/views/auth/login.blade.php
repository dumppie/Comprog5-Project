@extends('layouts.base')
@section('title', 'Login')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Login</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p class="mt-3 mb-0"><a href="{{ route('register') }}">Don't have an account? Register</a></p>
            </div>
        </div>
    </div>
@endsection
