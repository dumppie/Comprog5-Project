@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="card">
    <h2>Register</h2>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name *</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password">Password * (min 8 chars, 1 uppercase, 1 lowercase, 1 number)</label>
            <input id="password" type="password" name="password" required>
            @error('password') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password *</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>
        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number') }}">
            @error('contact_number') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="profile_photo">Profile Photo (optional, JPEG/PNG max 2MB)</label>
            <input id="profile_photo" type="file" name="profile_photo" accept="image/jpeg,image/png,image/jpg,image/gif">
            @error('profile_photo') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p style="margin-top:1rem;"><a href="{{ route('login') }}">Already have an account? Login</a></p>
</div>
@endsection
