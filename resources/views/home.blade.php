@extends('layouts.base')
@section('title', 'Home')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Welcome to {{ config('app.name') }}</h1>
                @guest
                    <p class="card-text">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary">Register</a>
                    </p>
                @else
                    <p class="card-text">Hello, {{ auth()->user()->name }}.</p>
                @endguest
            </div>
        </div>
    </div>
@endsection
