@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Edit Best Seller Product: {{ $product->name }}</h3>
                        <div>
                            <a href="{{ route('admin.best-seller.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Best Sellers
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.best-seller.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Product Details -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Product Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Product Name *</label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sku">SKU</label>
                                                    <input type="text" class="form-control" id="sku" name="sku" 
                                                           value="{{ old('sku', $product->sku) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="price">Price *</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                                               id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                                    </div>
                                                    @error('price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="sale_price">Sale Price</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="quantity">Quantity *</label>
                                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                                           id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                                                    @error('quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Status *</label>
                                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                                        <option value="publish" {{ old('status', $product->status) == 'publish' ? 'selected' : '' }}>Published</option>
                                                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="unit">Unit</label>
                                                    <input type="text" class="form-control" id="unit" name="unit" 
                                                           value="{{ old('unit', $product->unit) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dimensions -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="height">Height</label>
                                                    <input type="text" class="form-control" id="height" name="height" 
                                                           value="{{ old('height', $product->height) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="width">Width</label>
                                                    <input type="text" class="form-control" id="width" name="width" 
                                                           value="{{ old('width', $product->width) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="length">Length</label>
                                                    <input type="text" class="form-control" id="length" name="length" 
                                                           value="{{ old('length', $product->length) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="weight">Weight</label>
                                                    <input type="text" class="form-control" id="weight" name="weight" 
                                                           value="{{ old('weight', $product->weight) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Photo Management -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Photo Management</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Main Product Image -->
                                        <div class="form-group">
                                            <label for="main_image">Main Product Image</label>
                                            <div class="mb-2">
                                                @if($product->image)
                                                    <img src="{{ $product->image }}" alt="Main Image" class="img-fluid mb-2" style="max-height: 150px;">
                                                @endif
                                            </div>
                                            <input type="file" class="form-control-file" id="main_image" name="main_image" accept="image/*">
                                            <small class="form-text text-muted">Upload a new main product image</small>
                                        </div>

                                        <!-- Gallery Images -->
                                        <div class="form-group">
                                            <label for="gallery_images">Gallery Images</label>
                                            <input type="file" class="form-control-file" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                            <small class="form-text text-muted">Upload multiple images for the product gallery</small>
                                        </div>

                                        <!-- Current Gallery -->
                                        @if($product->gallery && is_array($product->gallery))
                                            <div class="form-group">
                                                <label>Current Gallery</label>
                                                <div class="row">
                                                    @foreach($product->gallery as $index => $image)
                                                        <div class="col-6 mb-2">
                                                            <div class="position-relative">
                                                                <img src="{{ $image['thumbnail'] ?? $image['original'] }}" 
                                                                     alt="Gallery Image {{ $index + 1 }}" 
                                                                     class="img-fluid" style="max-height: 80px;">
                                                                <button type="button" class="btn btn-sm btn-danger position-absolute" 
                                                                        style="top: 0; right: 0;" 
                                                                        onclick="removeGalleryImage({{ $index }})">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Image Preview -->
                                        <div id="image-preview" class="mt-3" style="display: none;">
                                            <label>Image Preview</label>
                                            <div id="preview-container" class="row"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Best Seller Settings -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Best Seller Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="is_best_seller" name="is_best_seller" value="1" 
                                                       {{ $product->is_best_seller ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_best_seller">
                                                    Mark as Best Seller
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="best_seller_position">Best Seller Position</label>
                                            <input type="number" class="form-control" id="best_seller_position" name="best_seller_position" 
                                                   value="{{ old('best_seller_position', $product->best_seller_position ?? 0) }}" min="0">
                                            <small class="form-text text-muted">Lower numbers appear first in best seller lists</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger" onclick="removeFromBestSellers()">
                                        <i class="fas fa-star"></i> Remove from Best Sellers
                                    </button>
                                    <div>
                                        <a href="{{ route('admin.best-seller.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Product
                                        </button>
                                    </div>
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
    // Image preview functionality
    $('#main_image, #gallery_images').change(function() {
        const files = this.files;
        const previewContainer = $('#preview-container');
        const imagePreview = $('#image-preview');
        
        previewContainer.empty();
        
        if (files.length > 0) {
            imagePreview.show();
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewHtml = `
                        <div class="col-6 mb-2">
                            <img src="${e.target.result}" class="img-fluid" style="max-height: 100px; object-fit: cover;">
                        </div>
                    `;
                    previewContainer.append(previewHtml);
                };
                
                reader.readAsDataURL(file);
            }
        } else {
            imagePreview.hide();
        }
    });

    // Remove gallery image
    window.removeGalleryImage = function(index) {
        if (confirm('Are you sure you want to remove this image from the gallery?')) {
            // Add hidden input to track removed images
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'removed_gallery_images[]';
            input.value = index;
            document.querySelector('form').appendChild(input);
            
            // Hide the image
            event.target.closest('.col-6').style.display = 'none';
        }
    };

    // Remove from best sellers
    window.removeFromBestSellers = function() {
        if (confirm('Are you sure you want to remove this product from best sellers?')) {
            $.ajax({
                url: '{{ route("admin.best-seller.updateStatus") }}',
                method: 'POST',
                data: {
                    product_id: {{ $product->id }},
                    status: false,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("admin.best-seller.index") }}';
                    }
                },
                error: function() {
                    alert('An error occurred while removing the product from best sellers.');
                }
            });
        }
    };
});
</script>
@endpush 