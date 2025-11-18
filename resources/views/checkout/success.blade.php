@extends('layouts.app')

@section('title', 'Order Success - ' . config('app.name'))

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="display-4 text-success mb-3">Order Placed Successfully!</h1>
                <p class="lead text-muted">Thank you for shopping with us. Your order has been confirmed.</p>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Order Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Order Number:</strong><br>
                            <span class="text-primary">{{ $order->order_number }}</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Order Date:</strong><br>
                            {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Payment Method:</strong><br>
                            {{ ucfirst($order->payment_method) }}
                        </div>
                        <div class="col-sm-6">
                            <strong>Payment Status:</strong><br>
                            <span class="badge bg-{{ $order->payment_status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Order Status:</strong><br>
                            <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Total Amount:</strong><br>
                            <span class="fw-bold fs-5 text-success">₹{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Delivery Address</h5>
                </div>
                <div class="card-body">
                    <strong>{{ $order->address->name }}</strong><br>
                    {{ $order->address->street_address }}<br>
                    {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}<br>
                    <strong>Phone:</strong> {{ $order->address->phone }}
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                    <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                        <img src="{{ $item->product->primaryImage ? asset($item->product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}"
                             alt="{{ $item->product->name }}"
                             class="img-fluid rounded me-3"
                             style="width: 60px; height: 60px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                            @if($item->variant_details)
                            <small class="text-muted">{{ $item->variant_details }}</small>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted">Qty: {{ $item->quantity }}</span>
                                <span class="fw-bold">₹{{ number_format($item->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount:</span>
                        <span>-₹{{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>₹{{ number_format($order->shipping_charge, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>₹{{ number_format($order->tax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span>₹{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-primary me-2">
                    <i class="fas fa-eye me-2"></i>View Order Details
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                </a>
            </div>

            @if($order->notes)
            <div class="alert alert-info mt-4">
                <strong>Order Notes:</strong> {{ $order->notes }}
            </div>
            @endif
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
</style>
@endsection
