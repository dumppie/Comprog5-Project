@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
<div class="card">
    <h2>Update your profile</h2>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <div class="form-group">
            <label for="name">Name *</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}">
            @error('contact_number') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
            @error('address') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Current profile photo</label>
            @if($user->profile_photo)
                <p><img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="avatar" style="width:60px;height:60px;"></p>
            @else
                <p>No photo</p>
            @endif
            <label for="profile_photo">New profile photo (optional, JPEG/PNG max 2MB)</label>
            <input id="profile_photo" type="file" name="profile_photo" accept="image/jpeg,image/png,image/jpg,image/gif">
            @error('profile_photo') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="current_password">Current password (required if changing password)</label>
            <input id="current_password" type="password" name="current_password">
            @error('current_password') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password">New password (leave blank to keep)</label>
            <input id="password" type="password" name="password">
            @error('password') <div class="errors">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" type="password" name="password_confirmation">
        </div>
        <button type="submit" class="btn btn-primary">Update profile</button>
    </form>
</div>
@endsection
