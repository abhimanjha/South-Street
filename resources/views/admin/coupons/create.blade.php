@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus me-2"></i>Create New Coupon</h2>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Coupons
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" 
                                   placeholder="e.g., SAVE20" style="text-transform: uppercase;">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="">Select Type</option>
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                   id="value" name="value" value="{{ old('value') }}" 
                                   step="0.01" min="0" placeholder="e.g., 20 or 500">
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">For percentage: enter number without % (e.g., 20 for 20%). For fixed: enter amount in rupees.</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="max_discount" class="form-label">Maximum Discount Amount</label>
                            <input type="number" class="form-control @error('max_discount') is-invalid @enderror" 
                                   id="max_discount" name="max_discount" value="{{ old('max_discount') }}" 
                                   step="0.01" min="0" placeholder="e.g., 1000">
                            @error('max_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Maximum discount amount for percentage coupons</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="min_purchase" class="form-label">Minimum Purchase Amount</label>
                            <input type="number" class="form-control @error('min_purchase') is-invalid @enderror" 
                                   id="min_purchase" name="min_purchase" value="{{ old('min_purchase') }}" 
                                   step="0.01" min="0" placeholder="e.g., 1000">
                            @error('min_purchase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Minimum cart value required to use this coupon</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Usage Limit</label>
                            <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                   id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" 
                                   min="1" placeholder="e.g., 100">
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Maximum number of times this coupon can be used</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: When the coupon becomes valid</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: When the coupon expires</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3" 
                              placeholder="Optional description for internal use">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                    <div class="form-text">Uncheck to create an inactive coupon</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Coupon
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endsection