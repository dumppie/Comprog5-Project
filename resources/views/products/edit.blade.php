@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Product: {{ $product->name }}</h3>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Product Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $product->name) }}" required maxlength="255">
                                    <small class="form-text text-muted">Maximum 255 characters</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category *</label>
                                    <select class="form-control" id="category" name="category" required>
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="4" maxlength="2000">{{ old('description', $product->description) }}</textarea>
                                    <small class="form-text text-muted">Maximum 2000 characters</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price (₱) *</label>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           value="{{ old('price', $product->price) }}" required 
                                           min="0" max="999999.99" step="0.01">
                                    <small class="form-text text-muted">Format: 999999.99</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_quantity">Stock Quantity *</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                           value="{{ old('stock_quantity', $product->stock_quantity) }}" required 
                                           min="0" max="999999">
                                    <small class="form-text text-muted">Whole numbers only</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="thumbnail_photo">Thumbnail Photo</label>
                                    <input type="file" class="form-control" id="thumbnail_photo" name="thumbnail_photo" 
                                           accept="image/jpeg,image/jpg,image/png">
                                    <small class="form-text text-muted">JPEG/PNG only, Max 2MB</small>
                                    @if($product->thumbnail_photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 200px;">
                                            <br>
                                            <small class="text-muted">Current thumbnail</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Current Product Photos</label>
                                    <div class="row">
                                        @foreach($product->photos as $index => $photo)
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                                         class="card-img-top" 
                                                         style="width: 100%; height: 150px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <small class="text-muted">Caption:</small>
                                                        <p class="card-text">{{ $photo->caption ?? 'No caption' }}</p>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                onclick="removePhoto({{ $photo->id }})">
                                                            <i class="fas fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="photos">Add More Photos</label>
                                    <input type="file" class="form-control" id="photos" name="photos[]" 
                                           multiple accept="image/jpeg,image/jpg,image/png">
                                    <small class="form-text text-muted">You can select multiple photos. JPEG/PNG only, Max 2MB each, Max 10 photos.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Photo Captions -->
                        <div id="photoCaptions" style="display: none;">
                            <!-- Photo captions will be added dynamically via JavaScript -->
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
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
                captionsContainer.html('<h6>New Photo Captions (Optional)</h6>');
                
                for (var i = 0; i < files.length; i++) {
                    var captionHtml = `
                        <div class="form-group mt-2">
                            <label>New Photo ${i + 1} Caption</label>
                            <input type="text" class="form-control" name="photo_captions[${i}]" 
                                   placeholder="Enter caption for new photo ${i + 1}" maxlength="255">
                        </div>
                    `;
                    captionsContainer.append(captionHtml);
                }
            } else {
                captionsContainer.hide();
            }
        });

        function removePhoto(photoId) {
            if (confirm('Are you sure you want to remove this photo?')) {
                $.ajax({
                    url: `/admin/products/photos/${photoId}/delete`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        }
    });
</script>
@endpush
