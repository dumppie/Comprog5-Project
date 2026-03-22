@extends('layouts.app')

@section('title', 'Import Products')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Import Products</h3>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Import Instructions</h5>
                        <p>Upload an Excel file (.xlsx or .xls) with the following columns:</p>
                        <ul>
                            <li><strong>name</strong> - Product name (required)</li>
                            <li><strong>category</strong> - Product category (optional: {{ implode(', ', array_keys(config('categories.product_categories'))) }})</li>
                            <li><strong>description</strong> - Product description (optional)</li>
                            <li><strong>price</strong> - Product price (required, numeric)</li>
                            <li><strong>stock_quantity</strong> - Stock quantity (required, integer)</li>
                        </ul>
                        <p class="mb-0">Maximum file size: 10MB</p>
                    </div>

                    <form id="importForm" action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excel_file">Select Excel File</label>
                            <input type="file" class="form-control-file" id="excel_file" name="excel_file" 
                                   accept=".xlsx,.xls" required>
                            @error('excel_file')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import Products
                            </button>
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
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Import Successful!',
                            html: `Successfully imported ${response.imported} products.`,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("admin.products.index") }}';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Import Failed',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'An error occurred during import.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>
@endpush
