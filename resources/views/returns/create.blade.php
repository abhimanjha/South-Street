@extends('layouts.app')

@section('title', 'Request Return - Order #' . $order->id)

@section('content')
<style>
/* Disable card hover jump effect */
.card:hover {
    transform: none !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
}
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-undo me-2"></i>Request Return</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Return Policy:</strong> You can return this order within 7 days of delivery. 
                        Refund will be processed within 24 hours after we receive the product.
                    </div>

                    <!-- Order Details -->
                    <div class="mb-4">
                        <h5>Order Details</h5>
                        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        <p><strong>Delivered On:</strong> {{ $order->delivered_at ? $order->delivered_at->format('M d, Y') : 'N/A' }}</p>
                        <p><strong>Total Amount:</strong> ₹{{ number_format($order->total, 2) }}</p>
                    </div>

                    <form action="{{ route('returns.store', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Return <span class="text-danger">*</span></label>
                            <select class="form-select @error('reason') is-invalid @enderror" id="reason" name="reason" required>
                                <option value="">Select a reason</option>
                                <option value="defective">Product is defective/damaged</option>
                                <option value="wrong_item">Wrong item delivered</option>
                                <option value="not_as_described">Product not as described</option>
                                <option value="size_issue">Size/fit issue</option>
                                <option value="quality_issue">Quality not satisfactory</option>
                                <option value="changed_mind">Changed my mind</option>
                                <option value="other">Other</option>
                            </select>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Additional Details</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Please provide more details about the issue...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Upload Photos (Optional)</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            <small class="text-muted">You can upload up to 5 photos showing the product condition</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Bank Account Details for Refund</h5>

                        <div class="mb-3">
                            <label for="account_holder_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror" 
                                   id="account_holder_name" name="account_holder_name" 
                                   value="{{ old('account_holder_name') }}" required>
                            @error('account_holder_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bank_account" class="form-label">Bank Account Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                   id="bank_account" name="bank_account" 
                                   value="{{ old('bank_account') }}" required>
                            @error('bank_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ifsc_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" 
                                   id="ifsc_code" name="ifsc_code" 
                                   value="{{ old('ifsc_code') }}" required>
                            @error('ifsc_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> Please ensure your bank details are correct. 
                            Refund will be processed to this account within 24 hours after we receive the product.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('account.orders.show', $order) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-paper-plane me-2"></i>Submit Return Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
