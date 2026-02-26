@extends('layouts.base')
@section('title', 'Register')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Register</h2>
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password * (min 8 chars, 1 uppercase, 1 lowercase, 1 number)</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input id="contact_number" type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                        @error('contact_number') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Profile Photo (optional, JPEG/PNG max 2MB)</label>
                        <input id="profile_photo" type="file" name="profile_photo" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                        @error('profile_photo') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                <p class="mt-3 mb-0"><a href="{{ route('login') }}">Already have an account? Login</a></p>
            </div>
        </div>
    </div>
@endsection
