@extends('layouts.admin')

@section('title', 'Trash Test')

@section('page-header', 'Trash Test')
@section('page-description', 'Simple test page')

@section('content')
<div class="card">
    <div class="card-body">
        <h3>Trash Page Works!</h3>
        <p>If you can see this, the route and controller are working properly.</p>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Back to Products</a>
    </div>
</div>
@endsection
