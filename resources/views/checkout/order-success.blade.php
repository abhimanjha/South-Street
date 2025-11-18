@extends('layouts.app')

@section('title', 'Order Successful - ' . config('app.name'))

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <!-- Success Icon -->
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>

                    <!-- Success Message -->
                    <h2 class="text-success mb-3">Payment Successful!</h2>
                    <p class="lead text-muted mb-4">Your order has been confirmed and is being processed.</p>

                    <!-- Order Details -->
                    <div class="order-summary bg-light p-4 rounded mb-4">
                        <h5 class="mb-3">Order Details</h5>
                        <div class="row">
                            <div class="col-sm-6 text-start">
                                <p class="mb-1"><strong>Order Number:</strong></p>
                                <p class="mb-1"><strong>Order Date:</strong></p>
                                <p class="mb-1"><strong>Payment Method:</strong></p>
                                <p class="mb-1"><strong>Total Amount:</strong></p>
                            </div>
                            <div class="col-sm-6 text-start">
                                <p class="mb-1">{{ $order->order_number }}</p>
                                <p class="mb-1">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                <p class="mb-1">{{ ucfirst($order->payment_method) }}</p>
                                <p class="mb-1 fw-bold">₹{{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items mb-4">
                        <h5 class="mb-3">Order Items</h5>
                        @foreach($order->items as $item)
                        <div class="d-flex align-items-center border-bottom py-3">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ $item->product->primaryImage ? asset($item->product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}"
                                     alt="{{ $item->product_name }}"
                                     class="rounded"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 text-start">
                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                @if($item->variant_details)
                                <small class="text-muted">{{ $item->variant_details }}</small>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span>Qty: {{ $item->quantity }}</span>
                                    <span class="fw-bold">₹{{ number_format($item->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Delivery Address -->
                    <div class="delivery-address bg-light p-3 rounded mb-4">
                        <h6 class="mb-2">Delivery Address</h6>
                        <p class="mb-0 text-muted">
                            {{ $order->address->name }}<br>
                            {{ $order->address->street }}, {{ $order->address->city }}<br>
                            {{ $order->address->state }} - {{ $order->address->pincode }}<br>
                            Phone: {{ $order->address->phone }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('account.orders.show', $order) }}" class="btn btn-primary me-2">
                            <i class="fas fa-eye me-2"></i>View Order Details
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>

                    <!-- Order Tracking Info -->
                    <div class="alert alert-info mt-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>What's Next?</strong> You will receive an email confirmation shortly.
                        Track your order status in your <a href="{{ route('account.orders') }}">account dashboard</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: checkmark 0.8s ease-in-out;
}

@keyframes checkmark {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.order-summary, .order-items, .delivery-address {
    text-align: left;
}

@media (max-width: 576px) {
    .action-buttons .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection
