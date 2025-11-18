@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus me-2"></i>Create New Product</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Short Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="full_description" class="form-label">Full Description</label>
                            <textarea class="form-control @error('full_description') is-invalid @enderror"
                                      id="full_description" name="full_description" rows="5">{{ old('full_description') }}</textarea>
                            @error('full_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Pricing & Inventory</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="discount_price" class="form-label">Discount Price (₹)</label>
                                <input type="number" class="form-control @error('discount_price') is-invalid @enderror"
                                       id="discount_price" name="discount_price" value="{{ old('discount_price') }}" step="0.01" min="0">
                                @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                       id="brand" name="brand" value="{{ old('brand') }}">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Product Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fabric" class="form-label">Fabric</label>
                                <input type="text" class="form-control @error('fabric') is-invalid @enderror"
                                       id="fabric" name="fabric" value="{{ old('fabric') }}">
                                @error('fabric')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="material" class="form-label">Material</label>
                                <input type="text" class="form-control @error('material') is-invalid @enderror"
                                       id="material" name="material" value="{{ old('material') }}">
                                @error('material')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="care_instructions" class="form-label">Care Instructions</label>
                            <textarea class="form-control @error('care_instructions') is-invalid @enderror"
                                      id="care_instructions" name="care_instructions" rows="3">{{ old('care_instructions') }}</textarea>
                            @error('care_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Product Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="images" class="form-label">Upload Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                   id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">You can select multiple images. First image will be set as primary.</div>
                            @error('images.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Product Variants</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-variant">
                            <i class="fas fa-plus me-1"></i>Add Variant
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="variants-container">
                            <!-- Variants will be added here dynamically -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Product Status -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-toggle-on me-2"></i>Product Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Product
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_new">
                                New Product
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_trending" name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_trending">
                                Trending Product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-save me-2"></i>Actions</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let variantCount = 0;

    // Add variant button
    document.getElementById('add-variant').addEventListener('click', function() {
        addVariant();
    });

    function addVariant(size = '', color = '', colorCode = '', stock = '', priceAdjustment = '') {
        const container = document.getElementById('variants-container');
        const variantHtml = `
            <div class="variant-item border rounded p-3 mb-3" data-variant-id="${variantCount}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Variant ${variantCount + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Size</label>
                        <input type="text" class="form-control" name="variants[${variantCount}][size]" value="${size}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Color</label>
                        <input type="text" class="form-control" name="variants[${variantCount}][color]" value="${color}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Color Code (Hex)</label>
                        <input type="color" class="form-control" name="variants[${variantCount}][color_code]" value="${colorCode || '#000000'}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="variants[${variantCount}][stock]" value="${stock}" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Price Adjustment (₹)</label>
                        <input type="number" class="form-control" name="variants[${variantCount}][price_adjustment]" value="${priceAdjustment}" step="0.01">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', variantHtml);
        variantCount++;
    }

    // Remove variant
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variant') || e.target.closest('.remove-variant')) {
            e.target.closest('.variant-item').remove();
        }
    });
});
</script>
@endsection
