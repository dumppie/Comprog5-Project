@extends('layouts.base')
@section('title', 'My Profile')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-pastry">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Account</p>
                        <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Update your profile</h2>
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input id="contact_number" type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $user->contact_number) }}">
                                @error('contact_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Current profile photo</label>
                                @if($user->profile_photo)
                                    <p><img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="rounded-circle" width="60" height="60" style="object-fit: cover;"></p>
                                @else
                                    <p class="text-muted">No photo</p>
                                @endif
                                <label for="profile_photo" class="form-label">New profile photo <span class="text-muted small">(optional, JPEG/PNG max 2MB)</span></label>
                                <input id="profile_photo" type="file" name="profile_photo" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                                @error('profile_photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current password <span class="text-muted small">(required if changing password)</span></label>
                                <input id="current_password" type="password" name="current_password" class="form-control">
                                @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New password <span class="text-muted small">(leave blank to keep)</span></label>
                                <input id="password" type="password" name="password" class="form-control">
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm new password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-pastry">Update profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
