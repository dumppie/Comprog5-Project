@extends('layouts.base')
@section('title', 'Welcome')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-4 text-center">
        <h1>{{ config('app.name') }}</h1>
        <p class="lead">E-commerce &amp; User Management</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go to Home</a>
    </div>
@endsection
