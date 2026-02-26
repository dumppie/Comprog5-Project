@extends('layouts.base')
@section('title', 'Register')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-pastry">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Create account</p>
                        <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Register</h2>
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password * <span class="text-muted small">(min 8 chars, 1 uppercase, 1 lowercase, 1 number)</span></label>
                                <input id="password" type="password" name="password" class="form-control" required>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input id="contact_number" type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                                @error('contact_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="profile_photo" class="form-label">Profile Photo <span class="text-muted small">(optional, JPEG/PNG max 2MB)</span></label>
                                <input id="profile_photo" type="file" name="profile_photo" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                                @error('profile_photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-pastry w-100">Register</button>
                        </form>
                        <p class="mt-4 mb-0 text-center"><a href="{{ route('login') }}">Already have an account? Sign in</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
