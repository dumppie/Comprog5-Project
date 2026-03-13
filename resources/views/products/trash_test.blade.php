@extends('layouts.base')
@section('title', 'Trash Test')
@section('body')
<div class="container py-5">
    <h2>Trash Test Page</h2>
    <p>This is a simple test to see if the trash view works.</p>
    <p>Found {{ $trashedProducts->count() }} deleted products.</p>
    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Back to Products</a>
</div>
@endsection
