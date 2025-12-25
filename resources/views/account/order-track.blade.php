@extends('layouts.app')

@push('styles')
<style>
/* CSS Reset for Track Order Page - Override External Styles */
.modern-track-override {
    position: relative !important;
}

.modern-track-override::before {
    content: 'MODERN TRACK ORDER CSS LOADED' !important;
    position: fixed !important;
    top: 10px !important;
    right: 10px !important;
    background: #007bff !important;
    color: white !important;
    padding: 5px 10px !important;
    border-radius: 5px !important;
    font-size: 12px !important;
    z-index: 9999 !important;
    font-weight: bold !important;
}

.modern-track-override * {
    animation: none !important;
}

.modern-track-override .card {
    perspective: none !important;
    border: none !important;
    border-radius: 1.2rem !important;
    overflow: hidden !important;
    animation: none !important;
}

.modern-track-override .card:hover {
    transform: none !important;
    box-shadow: none !important;
}

.modern-track-override .card::before {
    display: none !important;
}

/* Modern Track Order Page Styles - Override External CSS */
.modern-track-override.track-order-page {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    min-height: 100vh !important;
    padding: 2rem 0 !important;
}

/* Blue Gradient Header */
.modern-track-override .track-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    border-radius: 20px !important;
    padding: 3rem 2rem !important;
    color: white !important;
    margin-bottom: 2rem !important;
    box-shadow: 0 15px 35px rgba(0, 123, 255, 0.3) !important;
    position: relative !important;
    overflow: hidden !important;
}

.modern-track-override .track-header::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23pattern)"/></svg>') !important;
    opacity: 0.3 !important;
}

.modern-track-override .track-title {
    font-size: 2.5rem !important;
    font-weight: 700 !important;
    margin-bottom: 0.5rem !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3) !important;
    position: relative !important;
    z-index: 2 !important;
}

.modern-track-override .track-subtitle {
    font-size: 1.1rem !important;
    opacity: 0.9 !important;
    margin-bottom: 0 !important;
    position: relative !important;
    z-index: 2 !important;
}

/* Modern Cards */
.modern-track-override .track-card {
    background: white !important;
    border-radius: 20px !important;
    border: none !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    margin-bottom: 2rem !important;
    overflow: hidden !important;
    transition: all 0.3s ease !important;
}

.modern-track-override .track-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.modern-track-override .track-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    padding: 1.5rem 2rem !important;
    font-size: 1.2rem !important;
    font-weight: 600 !important;
    color: #1a1a1a !important;
    border-bottom: 1px solid rgba(0,0,0,0.1) !important;
}

.modern-track-override .track-card-body {
    padding: 2rem !important;
}

/* Form Styles */
.modern-track-override .form-label {
    font-weight: 600 !important;
    color: #1a1a1a !important;
    margin-bottom: 0.5rem !important;
    font-size: 0.9rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.modern-track-override .form-control {
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
    padding: 12px 16px !important;
    font-size: 1rem !important;
    transition: all 0.3s ease !important;
    background: white !important;
}

.modern-track-override .form-control:focus {
    outline: none !important;
    border-color: #007bff !important;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1) !important;
}

.modern-track-override .track-btn {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3) !important;
}

.modern-track-override .track-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4) !important;
}

/* Progress Timeline */
.modern-track-override .progress-timeline {
    position: relative !important;
    padding: 2rem 0 !important;
}

.modern-track-override .progress-step {
    display: flex !important;
    align-items: flex-start !important;
    margin-bottom: 2rem !important;
    position: relative !important;
}

.modern-track-override .progress-step:not(:last-child)::after {
    content: '' !important;
    position: absolute !important;
    left: 25px !important;
    top: 50px !important;
    width: 2px !important;
    height: 40px !important;
    background: #e9ecef !important;
    z-index: 1 !important;
}

.modern-track-override .progress-step.completed::after {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.modern-track-override .progress-step.active::after {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.modern-track-override .step-icon {
    width: 50px !important;
    height: 50px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-right: 1rem !important;
    font-size: 1.2rem !important;
    position: relative !important;
    z-index: 2 !important;
    transition: all 0.3s ease !important;
}

.modern-track-override .progress-step .step-icon {
    background: #e9ecef !important;
    color: #6c757d !important;
}

.modern-track-override .progress-step.completed .step-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3) !important;
}

.modern-track-override .progress-step.active .step-icon {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3) !important;
    animation: pulse 2s ease-in-out infinite !important;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1) !important;
    }
    50% {
        transform: scale(1.05) !important;
    }
}

.modern-track-override .step-content {
    flex: 1 !important;
}

.modern-track-override .step-content h6 {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    color: #1a1a1a !important;
    margin-bottom: 0.25rem !important;
}

.modern-track-override .step-content p {
    color: #6c757d !important;
    margin-bottom: 0 !important;
    font-size: 0.9rem !important;
}

/* Order Items */
.modern-track-override .order-item-track {
    display: flex !important;
    align-items: center !important;
    padding: 1rem 0 !important;
    border-bottom: 1px solid #f8f9fa !important;
}

.modern-track-override .order-item-track:last-child {
    border-bottom: none !important;
}

.modern-track-override .item-image-track {
    width: 60px !important;
    height: 60px !important;
    object-fit: cover !important;
    border-radius: 10px !important;
    margin-right: 1rem !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
}

.modern-track-override .item-details-track {
    flex: 1 !important;
}

.modern-track-override .item-details-track h6 {
    font-size: 1rem !important;
    font-weight: 600 !important;
    color: #1a1a1a !important;
    margin-bottom: 0.25rem !important;
}

.modern-track-override .item-details-track p {
    color: #6c757d !important;
    margin-bottom: 0 !important;
    font-size: 0.9rem !important;
}

.modern-track-override .item-total-track {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    color: #1a1a1a !important;
}

/* Badges */
.modern-track-override .badge {
    padding: 0.5rem 1rem !important;
    border-radius: 20px !important;
    font-size: 0.8rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.modern-track-override .bg-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.modern-track-override .bg-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.modern-track-override .bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
    color: #1a1a1a !important;
}

/* Alert */
.modern-track-override .alert {
    border-radius: 15px !important;
    border: none !important;
    padding: 1.5rem !important;
    font-size: 1rem !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.modern-track-override .alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
    color: #856404 !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modern-track-override .track-title {
        font-size: 2rem !important;
    }
    
    .modern-track-override .track-header {
        padding: 2rem 1.5rem !important;
    }
    
    .modern-track-override .track-card-body {
        padding: 1.5rem !important;
    }
    
    .modern-track-override .progress-step {
        margin-bottom: 1.5rem !important;
    }
    
    .modern-track-override .step-icon {
        width: 40px !important;
        height: 40px !important;
        font-size: 1rem !important;
    }
    
    .modern-track-override .progress-step:not(:last-child)::after {
        left: 20px !important;
        top: 40px !important;
        height: 30px !important;
    }
}
</style>
@endpush

@section('content')
<div class="track-order-page modern-track-override">
    <!-- Blue Gradient Header -->
    <div class="track-header">
        <div class="container">
            <h1 class="track-title">Track Your Order</h1>
            <p class="track-subtitle">Stay updated with your order's journey from our warehouse to your doorstep</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Order Search Card -->
                <div class="track-card">
                    <div class="track-card-header">
                        <i class="fas fa-search me-2"></i>Find Your Order
                    </div>
                    <div class="track-card-body">
                        <form method="GET" action="{{ route('account.order-track') }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="order_id" class="form-label">Order ID</label>
                                        <input type="text" class="form-control" id="order_id" name="order_id" 
                                               value="{{ request('order_id') }}" placeholder="Enter your order ID">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w-100 track-btn">
                                            <i class="fas fa-search me-2"></i>Track Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(isset($order))
                    <!-- Order Details Card -->
                    <div class="track-card">
                        <div class="track-card-header">
                            <i class="fas fa-package me-2"></i>Order #{{ $order->id }}
                        </div>
                        <div class="track-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'shipped' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Total Amount:</strong> ₹{{ number_format($order->total, 2) }}</p>
                                    <p><strong>Payment Status:</strong> 
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Timeline -->
                    <div class="track-card">
                        <div class="track-card-header">
                            <i class="fas fa-route me-2"></i>Order Progress
                        </div>
                        <div class="track-card-body">
                            <div class="progress-timeline">
                                <div class="progress-step {{ $order->status !== 'pending' ? 'completed' : 'active' }}">
                                    <div class="step-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6>Order Placed</h6>
                                        <p>{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                                    </div>
                                </div>

                                <div class="progress-step {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : ($order->status === 'processing' ? 'active' : '') }}">
                                    <div class="step-icon">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6>Order Processing</h6>
                                        <p>Your order is being prepared</p>
                                    </div>
                                </div>

                                <div class="progress-step {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ($order->status === 'shipped' ? 'active' : '') }}">
                                    <div class="step-icon">
                                        <i class="fas fa-shipping-fast"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6>Order Shipped</h6>
                                        <p>Your order is on its way</p>
                                    </div>
                                </div>

                                <div class="progress-step {{ $order->status === 'delivered' ? 'completed' : '' }}">
                                    <div class="step-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6>Order Delivered</h6>
                                        <p>Your order has been delivered</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="track-card">
                        <div class="track-card-header">
                            <i class="fas fa-shopping-bag me-2"></i>Order Items
                        </div>
                        <div class="track-card-body">
                            @foreach($order->items as $item)
                                <div class="order-item-track">
                                    @php
                                        $imagePath = $item->product->primaryImage ? $item->product->primaryImage->image_path : 'imgs/men1.jpg';
                                        $imageUrl = str_starts_with($imagePath, 'imgs/') ? asset($imagePath) : asset('storage/' . $imagePath);
                                    @endphp
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $item->product->name }}" class="item-image-track">
                                    
                                    <div class="item-details-track">
                                        <h6>{{ $item->product->name }}</h6>
                                        <p class="text-muted">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</p>
                                    </div>
                                    
                                    <div class="item-total-track">
                                        <strong>₹{{ number_format($item->price * $item->quantity, 2) }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif(request('order_id'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Order not found. Please check your order ID and try again.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection