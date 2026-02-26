@extends('layouts.base')
@section('title', 'Edit Profile')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Update your profile</h2>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input id="contact_number" type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $user->contact_number) }}">
                        @error('contact_number') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current profile photo</label>
                        @if($user->profile_photo)
                            <p><img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="rounded-circle" width="60" height="60"></p>
                        @else
                            <p class="text-muted">No photo</p>
                        @endif
                        <label for="profile_photo" class="form-label">New profile photo (optional, JPEG/PNG max 2MB)</label>
                        <input id="profile_photo" type="file" name="profile_photo" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                        @error('profile_photo') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current password (required if changing password)</label>
                        <input id="current_password" type="password" name="current_password" class="form-control">
                        @error('current_password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New password (leave blank to keep)</label>
                        <input id="password" type="password" name="password" class="form-control">
                        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm new password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Update profile</button>
                </form>
            </div>
        </div>
    </div>
@endsection
