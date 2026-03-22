@extends('layouts.base')
@section('title', 'My Profile')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Personal Information Section -->
                <div class="card card-pastry mb-4">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Personal Information</p>
                        <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Update your information</h2>
                        <form method="POST" action="{{ route('profile.update') }}" id="personal-info-form">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input id="first_name" type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                                    @error('first_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input id="middle_name" type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $user->middle_name) }}">
                                    @error('middle_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input id="last_name" type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                                    @error('last_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
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
                            <button type="submit" class="btn btn-pastry">Update Information</button>
                        </form>
                    </div>
                </div>

                <!-- Profile Photo Section -->
                <div class="card card-pastry mb-4">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Profile Photo</p>
                        <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Update your photo</h2>
                        <form method="POST" action="{{ route('profile.update') }}" id="photo-form" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Profile Photos</label>
                                <div class="row">
                                    <!-- Current Photo -->
                                    <div class="col-md-6">
                                        <h6 class="mb-3">Current Photo</h6>
                                        @if($user->profile_photo)
                                            <div class="border rounded p-3" style="background-color: #f8f9fa;">
                                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Current Avatar" class="rounded" width="180" height="180" style="object-fit: cover; display: block; margin: 0 auto;">
                                            </div>
                                        @else
                                            <div class="border rounded p-4" style="background-color: #f8f9fa; text-align: center; height: 220px; display: flex; align-items: center; justify-content: center;">
                                                <div>
                                                    <i class="bi bi-person-circle text-muted" style="font-size: 3rem;"></i>
                                                    <p class="text-muted mb-0 mt-2">No photo</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- New Photo Preview -->
                                    <div class="col-md-6">
                                        <h6 class="mb-3">New Photo Preview</h6>
                                        <div class="border rounded p-3" style="background-color: #f8f9fa;">
                                            <div id="photo-preview-container" style="height: 180px; display: flex; align-items: center; justify-content: center;">
                                                <div id="no-preview" class="text-center">
                                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                    <p class="text-muted mb-0 mt-2 small">No photo selected</p>
                                                </div>
                                                <img id="photo-preview" src="" alt="New Avatar" class="rounded d-none" width="180" height="180" style="object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Choose New Photo <span class="text-muted small">(optional, JPEG/PNG max 2MB)</span></label>
                                <div class="position-relative">
                                    <input id="profile_photo" type="file" name="profile_photo" class="form-control d-none" accept="image/jpeg,image/png,image/jpg,image/gif">
                                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('profile_photo').click()">
                                        <i class="bi bi-upload"></i> Choose Photo
                                    </button>
                                    <button type="button" id="clear-photo" class="btn btn-outline-danger ms-2 d-none">
                                        <i class="bi bi-x-circle"></i> Clear
                                    </button>
                                    <span id="file-name" class="ms-2 text-muted small">No file chosen</span>
                                </div>
                                @error('profile_photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-pastry">Update Photo</button>
                        </form>
                    </div>
                </div>

                <!-- Email Change Section -->
                <div class="card card-pastry mb-4">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Email Address</p>
                        <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Change your email</h2>
                        <form method="POST" action="{{ route('profile.update') }}" id="email-form">
                            @method('PATCH')
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">New Email Address *</label>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="current_password_email" class="form-label">Current Password *</label>
                                <input id="current_password_email" type="password" name="current_password" class="form-control" required>
                                <small class="text-muted">Enter your current password to confirm email change</small>
                                @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-pastry">Update Email</button>
                        </form>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="card card-pastry">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Security</p>
                        <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Change your password</h2>
                        <form method="POST" action="{{ route('profile.update') }}" id="password-form">
                            @method('PATCH')
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current password *</label>
                                <input id="current_password" type="password" name="current_password" class="form-control" required>
                                @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New password *</label>
                                <input id="password" type="password" name="password" class="form-control" required>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm new password *</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-pastry">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileName = file ? file.name : 'No file chosen';
    const fileExtension = file ? file.name.split('.').pop().toLowerCase() : '';
    
    // Update file name display
    document.getElementById('file-name').textContent = fileName;
    
    // Show/hide clear button
    const clearButton = document.getElementById('clear-photo');
    if (file) {
        clearButton.classList.remove('d-none');
    } else {
        clearButton.classList.add('d-none');
    }
    
    // Handle photo preview
    const preview = document.getElementById('photo-preview');
    const noPreview = document.getElementById('no-preview');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            noPreview.classList.add('d-none');
        };
        
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.classList.add('d-none');
        noPreview.classList.remove('d-none');
    }
});

// Clear photo functionality
document.getElementById('clear-photo').addEventListener('click', function() {
    // Clear the file input
    const fileInput = document.getElementById('profile_photo');
    fileInput.value = '';
    
    // Reset UI elements
    document.getElementById('file-name').textContent = 'No file chosen';
    document.getElementById('clear-photo').classList.add('d-none');
    
    // Clear preview
    const preview = document.getElementById('photo-preview');
    const noPreview = document.getElementById('no-preview');
    preview.src = '';
    preview.classList.add('d-none');
    noPreview.classList.remove('d-none');
});

// Simple form handling - just submit normally
document.getElementById('personal-info-form').addEventListener('submit', function(e) {
    // Clear all other fields before submission
    const allInputs = this.querySelectorAll('input, textarea');
    allInputs.forEach(input => {
        if (!['first_name', 'middle_name', 'last_name', 'contact_number', 'address', '_token', '_method'].includes(input.name)) {
            input.removeAttribute('name');
        }
    });
});

document.getElementById('photo-form').addEventListener('submit', function(e) {
    // Clear all other fields before submission
    const allInputs = this.querySelectorAll('input');
    allInputs.forEach(input => {
        if (!['profile_photo', '_token', '_method'].includes(input.name)) {
            input.removeAttribute('name');
        }
    });
});

document.getElementById('email-form').addEventListener('submit', function(e) {
    // Clear all other fields before submission
    const allInputs = this.querySelectorAll('input');
    allInputs.forEach(input => {
        if (!['email', 'current_password', '_token', '_method'].includes(input.name)) {
            input.removeAttribute('name');
        }
    });
});

document.getElementById('password-form').addEventListener('submit', function(e) {
    // Clear all other fields before submission
    const allInputs = this.querySelectorAll('input');
    allInputs.forEach(input => {
        if (!['current_password', 'password', 'password_confirmation', '_token', '_method'].includes(input.name)) {
            input.removeAttribute('name');
        }
    });
});
</script>
@endpush
