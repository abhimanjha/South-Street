@extends('layouts.admin')

@section('title', 'Send Discount Notification')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bell me-2"></i>Send Discount Notification</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Notification Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.notifications.send-discount') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">
                                <i class="fas fa-heading me-2"></i>Notification Title
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   placeholder="e.g., Special Weekend Sale!"
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">
                                <i class="fas fa-comment-alt me-2"></i>Message
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="4" 
                                      class="form-control @error('message') is-invalid @enderror" 
                                      placeholder="Enter your notification message here..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="discount_code" class="form-label fw-bold">
                                    <i class="fas fa-tag me-2"></i>Discount Code
                                </label>
                                <input type="text" 
                                       id="discount_code" 
                                       name="discount_code" 
                                       class="form-control @error('discount_code') is-invalid @enderror" 
                                       placeholder="e.g., SAVE20"
                                       value="{{ old('discount_code') }}"
                                       required>
                                @error('discount_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="discount_percentage" class="form-label fw-bold">
                                    <i class="fas fa-percent me-2"></i>Discount Percentage
                                </label>
                                <input type="number" 
                                       id="discount_percentage" 
                                       name="discount_percentage" 
                                       min="1" 
                                       max="100" 
                                       class="form-control @error('discount_percentage') is-invalid @enderror" 
                                       placeholder="e.g., 20"
                                       value="{{ old('discount_percentage') }}"
                                       required>
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="expiry_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-2"></i>Expiry Date
                            </label>
                            <input type="date" 
                                   id="expiry_date" 
                                   name="expiry_date" 
                                   class="form-control @error('expiry_date') is-invalid @enderror" 
                                   value="{{ old('expiry_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send to All Users
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Recipients:</strong> All registered users</p>
                    <p class="small mb-2"><strong>Delivery:</strong> Instant notification</p>
                    <p class="small mb-2"><strong>Display:</strong> Slide-in from right</p>
                    <p class="small mb-0"><strong>Duration:</strong> 10 seconds</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0 ps-3">
                        <li class="mb-2">Keep title short and catchy</li>
                        <li class="mb-2">Message should be clear and concise</li>
                        <li class="mb-2">Use uppercase for discount codes</li>
                        <li class="mb-0">Set realistic expiry dates</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
