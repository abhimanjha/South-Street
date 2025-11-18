@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order #{{ $order->id }}</h2>
        <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <img src="{{ $item->product->primaryImage ? asset($item->product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}"
                                 alt="{{ $item->product->name }}"
                                 style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px;">

                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <p class="text-muted mb-1">Quantity: {{ $item->quantity }}</p>
                                <p class="mb-0">₹{{ number_format($item->price, 2) }} each</p>
                            </div>

                            <div class="text-end">
                                <strong>₹{{ number_format($item->price * $item->quantity, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5>Order Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Order Placed</h6>
                                <p class="text-muted">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>

                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6>Order Processing</h6>
                                    <p class="text-muted">Your order is being prepared</p>
                                </div>
                            </div>
                        @endif

                        @if(in_array($order->status, ['shipped', 'delivered']))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6>Order Shipped</h6>
                                    <p class="text-muted">Your order has been shipped</p>
                                </div>
                            </div>
                        @endif

                        @if($order->status === 'delivered')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6>Order Delivered</h6>
                                    <p class="text-muted">Your order has been delivered successfully</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    @if($order->shipping_amount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount:</span>
                            <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Delivery Address</h5>
                </div>
                <div class="card-body">
                    <strong>{{ $order->address->name }}</strong><br>
                    {{ $order->address->phone }}<br>
                    {{ $order->address->street }}<br>
                    {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card">
                <div class="card-header">
                    <h5>Payment Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-content p {
    margin-bottom: 0;
    font-size: 0.9rem;
}
</style>
@endsection
