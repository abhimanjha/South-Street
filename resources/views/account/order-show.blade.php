@extends('layouts.app')

@section('content')
<style>
/* Modern Order Detail Page - Same as Track Order Design */
.order-detail-page {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.order-header {
    background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
    color: white;
    padding: 3rem 0;
    margin-bottom: 3rem;
    border-radius: 0 0 30px 30px;
    position: relative;
    overflow: hidden;
}

.order-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="20" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.order-header .container {
    position: relative;
    z-index: 2;
}

.order-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.order-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.header-btn {
    padding: 12px 24px;
    border: 2px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.1);
    color: white;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.header-btn:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.5);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.header-btn.btn-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    border-color: #f39c12;
}

.header-btn.btn-warning:hover {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    border-color: #e67e22;
}

/* Modern Cards */
.modern-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: none;
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modern-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid rgba(0,0,0,0.1);
    padding: 1.5rem;
    font-weight: 700;
    font-size: 1.1rem;
    color: #2c5aa0;
}

.modern-card-body {
    padding: 2rem;
}

/* Order Items Styling */
.order-item {
    display: flex;
    align-items: center;
    padding: 1.5rem 0;
    border-bottom: 1px solid #f1f3f4;
    transition: all 0.3s ease;
}

.order-item:hover {
    background: #f8f9fa;
    margin: 0 -2rem;
    padding-left: 2rem;
    padding-right: 2rem;
    border-radius: 10px;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 15px;
    margin-right: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.item-details h6 {
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.item-details .text-muted {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.item-price {
    font-weight: 700;
    font-size: 1.1rem;
    color: #2c5aa0;
}

/* Modern Timeline */
.modern-timeline {
    position: relative;
    padding-left: 40px;
}

.modern-timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding: 1rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.timeline-item:hover {
    transform: translateX(10px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.timeline-marker {
    position: absolute;
    left: -32px;
    top: 20px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #2c5aa0;
    z-index: 2;
}

.timeline-marker.bg-success {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
}

.timeline-marker.bg-info {
    background: linear-gradient(135deg, #3498db 0%, #5dade2 100%);
}

.timeline-marker.bg-primary {
    background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
}

.timeline-content h6 {
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.timeline-content p {
    color: #6c757d;
    margin-bottom: 0;
    font-size: 0.9rem;
}

/* Order Summary Styling */
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.summary-row:last-child {
    border-bottom: none;
    font-weight: 700;
    font-size: 1.1rem;
    color: #2c5aa0;
    padding-top: 1rem;
    border-top: 2px solid #2c5aa0;
}

.summary-row.text-success {
    color: #27ae60 !important;
}

/* Address and Payment Cards */
.info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 1px solid #e9ecef;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.info-card strong {
    color: #2c5aa0;
    display: block;
    margin-bottom: 0.5rem;
}

/* Status Badge */
.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.bg-success {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
    color: white;
}

.status-badge.bg-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white;
}

/* Alert Styling */
.modern-alert {
    border: none;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.modern-alert.alert-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1565c0;
    border-left: 4px solid #2196f3;
}

.modern-alert.alert-warning {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    color: #e65100;
    border-left: 4px solid #ff9800;
}

/* Responsive Design */
@media (max-width: 768px) {
    .order-title {
        font-size: 2rem;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .item-image {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .modern-timeline {
        padding-left: 30px;
    }
    
    .timeline-marker {
        left: -25px;
        width: 16px;
        height: 16px;
    }
}
</style>

<div class="order-detail-page">
    <!-- Modern Header -->
    <div class="order-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="order-title">Order #{{ $order->id }}</h1>
                    <p class="order-subtitle">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="header-actions">
                        @if($order->canBeReturned() && !$order->hasActiveReturn())
                            <a href="{{ route('returns.create', $order) }}" class="header-btn btn-warning">
                                <i class="fas fa-undo me-2"></i>Return Order
                            </a>
                        @endif
                        <a href="{{ route('account.orders') }}" class="header-btn">
                            <i class="fas fa-arrow-left me-2"></i>Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if($order->canBeReturned())
            <div class="modern-alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                This order can be returned within {{ 7 - now()->diffInDays($order->delivered_at) }} days.
            </div>
        @endif

        @if($order->hasActiveReturn())
            <div class="modern-alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                A return request is already in progress for this order.
                <a href="{{ route('returns.show', $order->returns()->latest()->first()) }}" class="alert-link">View Return Status</a>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <i class="fas fa-shopping-bag me-2"></i>Order Items
                    </div>
                    <div class="modern-card-body">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                @php
                                    $imagePath = $item->product->primaryImage ? $item->product->primaryImage->image_path : 'imgs/men1.jpg';
                                    $imageUrl = str_starts_with($imagePath, 'imgs/') ? asset($imagePath) : asset('storage/' . $imagePath);
                                @endphp
                                <img src="{{ $imageUrl }}"
                                     alt="{{ $item->product->name }}"
                                     class="item-image">

                                <div class="item-details flex-grow-1">
                                    <h6>{{ $item->product->name }}</h6>
                                    <p class="text-muted">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-muted">₹{{ number_format($item->price, 2) }} each</p>
                                </div>

                                <div class="item-price">
                                    ₹{{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <i class="fas fa-route me-2"></i>Order Timeline
                    </div>
                    <div class="modern-card-body">
                        <div class="modern-timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6><i class="fas fa-check-circle me-2"></i>Order Placed</h6>
                                    <p>{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                            </div>

                            @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6><i class="fas fa-cogs me-2"></i>Order Processing</h6>
                                        <p>Your order is being prepared with care</p>
                                    </div>
                                </div>
                            @endif

                            @if(in_array($order->status, ['shipped', 'delivered']))
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6><i class="fas fa-shipping-fast me-2"></i>Order Shipped</h6>
                                        <p>Your order is on its way to you</p>
                                    </div>
                                </div>
                            @endif

                            @if($order->status === 'delivered')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6><i class="fas fa-home me-2"></i>Order Delivered</h6>
                                        <p>Your order has been delivered successfully</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <i class="fas fa-receipt me-2"></i>Order Summary
                    </div>
                    <div class="modern-card-body">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        @if($order->shipping_amount > 0)
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                        @endif

                        @if($order->discount_amount > 0)
                            <div class="summary-row text-success">
                                <span>Discount:</span>
                                <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif

                        <div class="summary-row">
                            <span>Total:</span>
                            <span>₹{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <i class="fas fa-map-marker-alt me-2"></i>Delivery Address
                    </div>
                    <div class="modern-card-body">
                        <div class="info-card">
                            <strong>{{ $order->address->name }}</strong>
                            <div>{{ $order->address->phone }}</div>
                            <div>{{ $order->address->street }}</div>
                            <div>{{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <i class="fas fa-credit-card me-2"></i>Payment Information
                    </div>
                    <div class="modern-card-body">
                        <div class="info-card">
                            <p><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                            <p><strong>Status:</strong>
                                <span class="status-badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
