@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="card">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email *</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password">Password *</label>
            <input id="password" type="password" name="password" required>
            @error('password') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="remember"> Remember me</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p style="margin-top:1rem;"><a href="{{ route('register') }}">Don't have an account? Register</a></p>
</div>
@endsection
