@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="card">
    <h1>Welcome to {{ config('app.name') }}</h1>
    @guest
        <p><a href="{{ route('login') }}" class="btn btn-primary">Login</a> or <a href="{{ route('register') }}" class="btn btn-secondary">Register</a></p>
    @else
        <p>Hello, {{ auth()->user()->name }}.</p>
    @endguest
</div>
@endsection
