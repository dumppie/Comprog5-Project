@extends('layouts.admin')

@section('title', 'Create Product')

@section('page-header', 'Create New Product')
@section('page-description', 'Add a new product to your inventory')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card bg-white border-2 shadow-lg" style="border-color: var(--pastry-sand);">
                <div class="card-header bg-white border-0" style="border-bottom: 2px solid var(--pastry-sand) !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0" style="color: var(--pastry-brown);">
                                <i class="fas fa-plus-circle me-2"></i>Create New Product
                            </h3>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Products
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success border-0" style="background-color: #D4EDDA; border-left: 4px solid #28A745 !important;">
                            <h5 class="alert-heading">
                                <i class="fas fa-check-circle me-2"></i>Success!
                            </h5>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0" style="background-color: #F8D7DA; border-left: 4px solid #DC3545 !important;">
                            <h5 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>Error!
                            </h5>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0" style="background-color: #F8D7DA; border-left: 4px solid #DC3545 !important;">
                            <h5 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:
                            </h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3" style="color: var(--pastry-brown); border-bottom: 2px solid var(--pastry-sand); padding-bottom: 8px;">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label fw-semibold" style="color: var(--pastry-text);">
                                        Product Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control border-2" id="name" name="name" 
                                           value="{{ old('name') }}" required maxlength="255"
                                           style="border-color: var(--pastry-sand); focus-border-color: var(--pastry-caramel);">
                                    <small class="form-text text-muted">Maximum 255 characters</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label fw-semibold" style="color: var(--pastry-text);">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control border-2" id="category" name="category" required
                                            style="border-color: var(--pastry-sand);">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" 
                                                    {{ old('category') == $category ? 'selected' : '' }}>
                                                {{ ucfirst($category) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3" style="color: var(--pastry-brown); border-bottom: 2px solid var(--pastry-sand); padding-bottom: 8px;">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </h5>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label fw-semibold" style="color: var(--pastry-text);">
                                        Product Description
                                    </label>
                                    <textarea class="form-control border-2" id="description" name="description" 
                                              rows="4" maxlength="2000" placeholder="Enter a detailed description of your product..."
                                              style="border-color: var(--pastry-sand);">{{ old('description') }}</textarea>
                                    <small class="form-text text-muted">Maximum 2000 characters</small>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Inventory Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3" style="color: var(--pastry-brown); border-bottom: 2px solid var(--pastry-sand); padding-bottom: 8px;">
                                    <i class="fas fa-tag me-2"></i>Pricing & Inventory
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label fw-semibold" style="color: var(--pastry-text);">
                                        Price (₱) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-color: var(--pastry-sand); background-color: var(--pastry-cream);">
                                            <i class="fas fa-peso-sign"></i>
                                        </span>
                                        <input type="number" class="form-control border-2" id="price" name="price" 
                                               value="{{ old('price') }}" required 
                                               min="0" max="999999.99" step="0.01"
                                               style="border-color: var(--pastry-sand);">
                                    </div>
                                    <small class="form-text text-muted">Format: 999999.99</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="stock_quantity" class="form-label fw-semibold" style="color: var(--pastry-text);">
                                        Stock Quantity <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-color: var(--pastry-sand); background-color: var(--pastry-cream);">
                                            <i class="fas fa-boxes"></i>
                                        </span>
                                        <input type="number" class="form-control border-2" id="stock_quantity" name="stock_quantity" 
                                               value="{{ old('stock_quantity') }}" required 
                                               min="0" max="999999"
                                               style="border-color: var(--pastry-sand);">
                                    </div>
                                    <small class="form-text text-muted">Whole numbers only</small>
                                </div>
                            </div>
                        </div>

                        <!-- Media Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3" style="color: var(--pastry-brown); border-bottom: 2px solid var(--pastry-sand); padding-bottom: 8px;">
                                    <i class="fas fa-images me-2"></i>Product Images
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="thumbnail_photo" class="form-label fw-semibold" style="color: var(--pastry-text);">
                                        Thumbnail Photo
                                    </label>
                                    <input type="file" class="form-control border-2" id="thumbnail_photo" name="thumbnail_photo" 
                                           accept="image/jpeg,image/jpg,image/png"
                                           style="border-color: var(--pastry-sand);">
                                    <small class="form-text text-muted">JPEG/PNG only, Max 2MB</small>
                                    @if(old('thumbnail_photo'))
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . old('thumbnail_photo')) }}" 
                                                 class="img-thumbnail border-2" 
                                                 style="max-width: 200px; border-color: var(--pastry-sand);">
                                            <br>
                                            <small class="text-muted">Current thumbnail</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label fw-semibold" style="color: var(--pastry-text);">
                                        Product Status
                                    </label>
                                    <select class="form-control border-2" id="status" name="status"
                                            style="border-color: var(--pastry-sand);">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            <i class="fas fa-pause-circle me-1"></i> Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Photos Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="photos" class="form-label fw-semibold" style="color: var(--pastry-text);">
                                        <i class="fas fa-camera me-2"></i>Additional Photos
                                    </label>
                                    <input type="file" class="form-control border-2" id="photos" name="photos[]" 
                                           multiple accept="image/jpeg,image/jpg,image/png"
                                           style="border-color: var(--pastry-sand);">
                                    <small class="form-text text-muted">You can select multiple photos. JPEG/PNG only, Max 2MB each, Max 10 photos.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Photo Captions -->
                        <div id="photoCaptions" style="display: none;" class="mb-4">
                            <!-- Photo captions will be added dynamically via JavaScript -->
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-pastry btn-lg">
                                        <i class="fas fa-save me-2"></i>Create Product
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                    <h5 class="mb-3" style="color: var(--pastry-brown); border-bottom: 2px solid var(--pastry-sand); padding-bottom: 8px;">
                        <i class="fas fa-comment-dots me-2"></i>Photo Captions (Optional)
                    </h5>
                `);
                
                for (var i = 0; i < files.length; i++) {
                    var captionHtml = `
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold" style="color: var(--pastry-text);">
                                Photo ${i + 1} Caption
                            </label>
                            <input type="text" class="form-control border-2" name="photo_captions[${i}]" 
                                   placeholder="Enter caption for photo ${i + 1}" maxlength="255"
                                   style="border-color: var(--pastry-sand);">
                        </div>
                    `;
                    captionsContainer.append(captionHtml);
                }
            } else {
                captionsContainer.hide();
            }
        });

        // Auto-hide success messages after 5 seconds
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush
