@extends('layouts.base')
@section('title', 'Welcome')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row justify-content-center py-5">
            <div class="col-md-8 text-center">
                <h1 class="font-serif display-5 mb-3" style="color: var(--pastry-brown);">La Petite Pâtisserie</h1>
                <p class="text-muted mb-4">Pastry Shop — E-commerce &amp; User Management</p>
                <a href="{{ route('home') }}" class="btn btn-pastry btn-lg">Go to Home</a>
            </div>
        </div>
    </div>
@endsection
