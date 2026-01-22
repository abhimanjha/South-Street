@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit me-2"></i>Edit Product: {{ $product->name }}</h2>
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

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                                       id="name" name="name" value="{{ old('name', $product->name) }}" required>
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
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                      id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="full_description" class="form-label">Full Description</label>
                            <textarea class="form-control @error('full_description') is-invalid @enderror"
                                      id="full_description" name="full_description" rows="5">{{ old('full_description', $product->full_description) }}</textarea>
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
                                       id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="discount_price" class="form-label">Discount Price (₹)</label>
                                <input type="number" class="form-control @error('discount_price') is-invalid @enderror"
                                       id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" step="0.01" min="0">
                                @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                       id="brand" name="brand" value="{{ old('brand', $product->brand) }}">
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
                                       id="fabric" name="fabric" value="{{ old('fabric', $product->fabric) }}">
                                @error('fabric')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="material" class="form-label">Material</label>
                                <input type="text" class="form-control @error('material') is-invalid @enderror"
                                       id="material" name="material" value="{{ old('material', $product->material) }}">
                                @error('material')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="care_instructions" class="form-label">Care Instructions</label>
                            <textarea class="form-control @error('care_instructions') is-invalid @enderror"
                                      id="care_instructions" name="care_instructions" rows="3">{{ old('care_instructions', $product->care_instructions) }}</textarea>
                            @error('care_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                @if($product->images->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Current Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($product->images->sortBy('order') as $image)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ Storage::url($image->image_path) }}"
                                         class="card-img-top" alt="Product Image" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($image->is_primary)
                                                <span class="badge bg-primary">Primary</span>
                                            @else
                                                <form method="POST" action="{{ route('admin.products.images.delete', [$product, $image]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure you want to delete this image?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Add New Images -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="images" class="form-label">Upload Additional Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                   id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">You can select multiple images. They will be added to existing images.</div>
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
                            @if($product->variants->count() > 0)
                                @foreach($product->variants as $index => $variant)
                                <div class="variant-item border rounded p-3 mb-3" data-variant-id="{{ $index }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Variant {{ $index + 1 }}</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Size</label>
                                            <input type="text" class="form-control" name="variants[{{ $index }}][size]" value="{{ $variant->size }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Color</label>
                                            <input type="text" class="form-control" name="variants[{{ $index }}][color]" value="{{ $variant->color }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Color Code (Hex)</label>
                                            <input type="color" class="form-control" name="variants[{{ $index }}][color_code]" value="{{ $variant->color_code ?: '#000000' }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="variants[{{ $index }}][stock]" value="{{ $variant->stock }}" min="0" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Price Adjustment (₹)</label>
                                            <input type="number" class="form-control" name="variants[{{ $index }}][price_adjustment]" value="{{ $variant->price_adjustment }}" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
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
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Product
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_new">
                                New Product
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_trending" name="is_trending" value="1" {{ old('is_trending', $product->is_trending) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_trending">
                                Trending Product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info me-2"></i>Product Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>SKU:</strong> {{ $product->sku }}
                        </div>
                        <div class="mb-2">
                            <strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}
                        </div>
                        <div class="mb-2">
                            <strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y') }}
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
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Product Form (Separate) -->
    <div class="row mt-4">
        <div class="col-lg-4 offset-lg-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Once you delete this product, there is no going back. Please be certain.</p>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                          onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let variantCount = {{ $product->variants->count() }};

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
