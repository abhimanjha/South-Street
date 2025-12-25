@extends('layouts.app')

@section('title', 'Order Successful - ' . config('app.name'))

@section('content')
<style>
/* Order Success Page Styles */
.order-success-page {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.success-container {
    max-width: 900px;
    margin: 0 auto;
}

/* Disable all card hover effects on success page */
.order-success-page .card:hover {
    transform: none !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
}

.order-success-page .card:hover .card-img-top,
.order-success-page .card:hover .bg-light {
    transform: none !important;
}

.order-success-page .card:hover::before {
    opacity: 0 !important;
}

/* Success Header */
.success-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
}

.success-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    animation: successPulse 2s ease-in-out;
}

@keyframes successPulse {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.1); opacity: 0.8; }
    100% { transform: scale(1); opacity: 1; }
}

.success-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.success-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
}

/* Modern Cards */
.modern-card {
    background: white;
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: none !important;
}

.modern-card-header {
    background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
    color: white;
    padding: 1.25rem 1.5rem;
    border: none;
    font-weight: 600;
    font-size: 1.1rem;
}

.modern-card-body {
    padding: 1.5rem;
}

/* Order Details Grid */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.detail-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 12px;
    border-left: 4px solid #28a745;
}

.detail-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    color: #212529;
    font-weight: 500;
}

/* Order Items */
.order-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 1rem;
    transition: none !important;
}

.order-item:last-child {
    margin-bottom: 0;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
    margin-right: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.item-details {
    flex: 1;
}

.item-name {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
}

.item-variant {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.item-quantity {
    font-size: 0.9rem;
    color: #495057;
}

.item-price {
    font-weight: 700;
    color: #28a745;
    font-size: 1.1rem;
}

/* Address Card */
.address-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 1.5rem;
    border-left: 4px solid #17a2b8;
}

.address-name {
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.address-details {
    color: #495057;
    line-height: 1.6;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.btn-modern {
    padding: 0.75rem 2rem;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-modern-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.btn-modern-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 123, 255, 0.4);
    color: white;
}

.btn-modern-outline {
    background: white;
    color: #007bff;
    border: 2px solid #007bff;
}

.btn-modern-outline:hover {
    background: #007bff;
    color: white;
    transform: translateY(-2px);
}

/* Info Alert */
.info-alert {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border: none;
    border-radius: 12px;
    border-left: 4px solid #17a2b8;
    padding: 1rem 1.5rem;
    margin-top: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .success-header {
        padding: 2rem 1rem;
        margin: 1rem;
        border-radius: 16px;
    }
    
    .success-title {
        font-size: 2rem;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
    
    .item-image {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-modern {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style>

<div class="order-success-page">
    <div class="container">
        <div class="success-container">
            <!-- Success Header -->
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="success-title">Payment Successful!</h1>
                <p class="success-subtitle">Your order has been confirmed and is being processed.</p>
            </div>

            <!-- Order Details -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <i class="fas fa-receipt me-2"></i>Order Details
                </div>
                <div class="modern-card-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Order Number</div>
                            <div class="detail-value">#{{ $order->order_number }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Order Date</div>
                            <div class="detail-value">{{ $order->created_at->format('M d, Y H:i') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Payment Method</div>
                            <div class="detail-value">{{ ucfirst($order->payment_method) }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Total Amount</div>
                            <div class="detail-value" style="font-size: 1.25rem; font-weight: 700; color: #28a745;">
                                ₹{{ number_format($order->total, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <i class="fas fa-shopping-bag me-2"></i>Order Items ({{ $order->items->count() }} {{ $order->items->count() === 1 ? 'item' : 'items' }})
                </div>
                <div class="modern-card-body">
                    @foreach($order->items as $item)
                    <div class="order-item">
                        <img src="{{ $item->product->primaryImage ? asset($item->product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}"
                             alt="{{ $item->product_name }}"
                             class="item-image">
                        <div class="item-details">
                            <div class="item-name">{{ $item->product_name }}</div>
                            @if($item->variant_details)
                            <div class="item-variant">{{ $item->variant_details }}</div>
                            @endif
                            <div class="item-quantity">Quantity: {{ $item->quantity }}</div>
                        </div>
                        <div class="item-price">₹{{ number_format($item->total, 2) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <i class="fas fa-map-marker-alt me-2"></i>Delivery Address
                </div>
                <div class="modern-card-body">
                    <div class="address-card">
                        <div class="address-name">{{ $order->address->name }}</div>
                        <div class="address-details">
                            {{ $order->address->street }}, {{ $order->address->city }}<br>
                            {{ $order->address->state }} - {{ $order->address->pincode }}<br>
                            <strong>Phone:</strong> {{ $order->address->phone }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('account.orders.show', $order) }}" class="btn-modern btn-modern-primary">
                    <i class="fas fa-eye"></i>Track Order
                </a>
                <a href="{{ route('products.index') }}" class="btn-modern btn-modern-outline">
                    <i class="fas fa-shopping-bag"></i>Continue Shopping
                </a>
            </div>

            <!-- Order Tracking Info -->
            <div class="info-alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>What's Next?</strong> You will receive an email confirmation shortly.
                Track your order status in your <a href="{{ route('account.orders') }}" style="color: #0c5460; font-weight: 600;">account dashboard</a>.
            </div>
        </div>
    </div>
</div>
@endsection
