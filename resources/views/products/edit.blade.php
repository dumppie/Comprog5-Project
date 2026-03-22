@extends('layouts.admin')

@section('title', 'Edit Product')

@section('page-header', 'Edit Product')
@section('page-description', 'Update product information and photos')

@section('page-actions')
<a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-pastry">
    <i class="fas fa-eye me-2"></i>View Product
</a>
<a href="{{ route('admin.products.index') }}" class="btn btn-outline-pastry">
    <i class="fas fa-arrow-left me-2"></i>Back to Products
</a>
@endsection

@section('content')
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0" style="background-color: #F8D7DA; border-left: 4px solid #DC3545 !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

<div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
    <div class="card-header bg-white border-0" style="border-bottom: 2px solid var(--pastry-sand) !important;">
        <h5 class="mb-0" style="color: var(--pastry-brown);">
            <i class="fas fa-edit me-2"></i>Edit Product: {{ $product->name }}
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                            Product Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control border-2" id="name" name="name" 
                               value="{{ old('name', $product->name) }}" required maxlength="255"
                               style="border-color: var(--pastry-sand);">
                        <small class="form-text text-muted">Maximum 255 characters</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category" class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                            Category <span class="text-danger">*</span>
                        </label>
                        <select class="form-control border-2" id="category" name="category" required
                                style="border-color: var(--pastry-sand);">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" 
                                        {{ old('category', $product->category) == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description" class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                            Description
                        </label>
                        <textarea class="form-control border-2" id="description" name="description" 
                                  rows="4" maxlength="2000" style="border-color: var(--pastry-sand);"
                                  placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
                        <small class="form-text text-muted">Maximum 2000 characters</small>
                    </div>
                </div>
            </div>

            <!-- Price and Stock -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="price" class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                            Price (₱) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-color: var(--pastry-sand); background-color: var(--pastry-cream);">
                                ₱
                            </span>
                            <input type="number" class="form-control border-2" id="price" name="price" 
                                   value="{{ old('price', $product->price) }}" required 
                                   min="0" max="999999.99" step="0.01" style="border-color: var(--pastry-sand);">
                        </div>
                        <small class="form-text text-muted">Format: 999999.99</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="stock_quantity" class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                            Stock Quantity <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-color: var(--pastry-sand); background-color: var(--pastry-cream);">
                                <i class="fas fa-box"></i>
                            </span>
                            <input type="number" class="form-control border-2" id="stock_quantity" name="stock_quantity" 
                                   value="{{ old('stock_quantity', $product->stock_quantity) }}" required 
                                   min="0" max="999999" style="border-color: var(--pastry-sand);">
                        </div>
                        <small class="form-text text-muted">Whole numbers only</small>
                    </div>
                </div>
            </div>

            <!-- Thumbnail and Status -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="thumbnail_photo" class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                            Thumbnail Photo
                        </label>
                        <input type="file" class="form-control border-2" id="thumbnail_photo" name="thumbnail_photo" 
                               accept="image/jpeg,image/jpg,image/png" style="border-color: var(--pastry-sand);">
                        <small class="form-text text-muted">JPEG/PNG only, Max 2MB</small>
                        @if($product->thumbnail_photo)
                            <div class="mt-3 p-3 rounded" style="background-color: var(--pastry-cream);">
                                <p class="mb-2" style="color: var(--pastry-brown); font-weight: 600;">Current Thumbnail:</p>
                                <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" 
                                     class="img-fluid rounded" 
                                     style="max-width: 200px; border: 2px solid var(--pastry-sand);">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                            Status
                        </label>
                        <select class="form-control border-2" id="status" name="status" style="border-color: var(--pastry-sand);">
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle me-1"></i> Active
                            </option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                <i class="fas fa-pause-circle me-1"></i> Inactive
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Current Photos -->
            @if($product->photos->count() > 0)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                                <i class="fas fa-images me-2"></i>Current Product Photos ({{ $product->photos->count() }})
                            </label>
                            <div class="row">
                                @foreach($product->photos as $index => $photo)
                                    <div class="col-md-3 mb-3">
                                        <div class="card border-2 shadow" style="border-color: var(--pastry-sand);">
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                                 class="card-img-top" 
                                                 style="width: 100%; height: 150px; object-fit: cover;">
                                            <div class="card-body p-3">
                                                @if($photo->caption)
                                                    <small class="text-muted">Caption:</small>
                                                    <p class="card-text mb-2">{{ $photo->caption }}</p>
                                                @endif
                                                @if($photo->is_thumbnail)
                                                    <span class="badge mb-2" style="background-color: var(--pastry-caramel); color: white;">
                                                        <i class="fas fa-star me-1"></i>Thumbnail
                                                    </span>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-danger w-100" 
                                                        onclick="removePhoto({{ $photo->id }})">
                                                    <i class="fas fa-trash me-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Add More Photos -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="photos" class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                            <i class="fas fa-plus-circle me-2"></i>Add More Photos
                        </label>
                        <input type="file" class="form-control border-2" id="photos" name="photos[]" 
                               multiple accept="image/jpeg,image/jpg,image/png" style="border-color: var(--pastry-sand);">
                        <small class="form-text text-muted">You can select multiple photos. JPEG/PNG only, Max 2MB each, Max 10 photos.</small>
                    </div>
                </div>
            </div>

            <!-- Dynamic Photo Captions -->
            <div id="photoCaptions" style="display: none;" class="mb-4">
                <!-- Photo captions will be added dynamically via JavaScript -->
            </div>

            <!-- Form Actions -->
            <div class="form-group">
                <button type="submit" class="btn btn-pastry">
                    <i class="fas fa-save me-2"></i>Update Product
                </button>
                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-pastry">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#photos').on('change', function() {
            var files = this.files;
            var captionsContainer = $('#photoCaptions');
            captionsContainer.empty();
            
            if (files.length > 0) {
                captionsContainer.show();
                captionsContainer.html(`
                    <div class="card bg-white border-2 shadow" style="border-color: var(--pastry-sand);">
                        <div class="card-header bg-white border-0" style="border-bottom: 2px solid var(--pastry-sand) !important;">
                            <h6 class="mb-0" style="color: var(--pastry-brown);">
                                <i class="fas fa-comment-alt me-2"></i>New Photo Captions (Optional)
                            </h6>
                        </div>
                        <div class="card-body">
                `);
                
                for (var i = 0; i < files.length; i++) {
                    var captionHtml = `
                        <div class="form-group mb-3">
                            <label class="form-label" style="color: var(--pastry-brown); font-weight: 600;">
                                New Photo ${i + 1} Caption
                            </label>
                            <input type="text" class="form-control border-2" name="photo_captions[${i}]" 
                                   placeholder="Enter caption for new photo ${i + 1}" maxlength="255"
                                   style="border-color: var(--pastry-sand);">
                        </div>
                    `;
                    captionsContainer.find('.card-body').append(captionHtml);
                }
                
                captionsContainer.append('</div></div>');
            } else {
                captionsContainer.hide();
            }
        });

        window.removePhoto = function(photoId) {
            if (confirm('Are you sure you want to remove this photo? This action cannot be undone.')) {
                $.ajax({
                    url: `/admin/products/photos/${photoId}/delete`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while removing the photo.');
                    }
                });
            }
        };
    });
</script>
@endpush
