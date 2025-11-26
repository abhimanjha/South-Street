@extends('layouts.app')

@section('content')
<style>
/* Disable card hover jump effect on order tracking page */
.card:hover {
    transform: none !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
}
</style>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Track Order #{{ $order->id }}</h2>
        <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Order
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Order Tracking</h5>
                </div>
                <div class="card-body">
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="progress" style="height: 10px;">
                            @php
                                $progress = match($order->status) {
                                    'delivered' => 100,
                                    'shipped', 'out_for_delivery' => 75,
                                    'processing' => 50,
                                    'confirmed' => 25,
                                    default => 0
                                };
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">Order Progress: {{ $progress }}%</small>
                    </div>

                    <!-- Horizontal Timeline -->
                    <div class="timeline-container">
                        <div class="timeline-step" data-status="order-placed">
                            <div class="timeline-icon {{ $order->status !== 'pending' ? 'completed' : 'active' }}">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Order Placed</h6>
                                <p class="text-muted small">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                                <span class="badge bg-success">Completed</span>
                            </div>
                        </div>

                        <div class="timeline-step" data-status="confirmed">
                            <div class="timeline-icon {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered']) ? 'completed' : '' }}">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Order Confirmed</h6>
                                <p class="text-muted small">Your order has been confirmed</p>
                                @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered']))
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </div>
                        </div>

                        <div class="timeline-step" data-status="processing">
                            <div class="timeline-icon {{ in_array($order->status, ['processing', 'shipped', 'out_for_delivery', 'delivered']) ? 'completed' : '' }}">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Order Packed</h6>
                                <p class="text-muted small">Your order is being packed</p>
                                @if(in_array($order->status, ['processing', 'shipped', 'out_for_delivery', 'delivered']))
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </div>
                        </div>

                        <div class="timeline-step" data-status="shipped">
                            <div class="timeline-icon {{ in_array($order->status, ['shipped', 'out_for_delivery', 'delivered']) ? 'completed' : '' }}">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Shipped</h6>
                                <p class="text-muted small">Your order has been shipped</p>
                                @if(in_array($order->status, ['shipped', 'out_for_delivery', 'delivered']))
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </div>
                        </div>

                        <div class="timeline-step" data-status="delivered">
                            <div class="timeline-icon {{ $order->status === 'delivered' ? 'completed' : '' }}">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Delivered</h6>
                                <p class="text-muted small">Your order has been delivered</p>
                                @if($order->status === 'delivered')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'secondary'))) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="card">
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
        </div>
    </div>
</div>

<style>
.tracking-container {
    position: relative;
}

.tracking-container::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
    z-index: 1;
}

.tracking-step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 30px;
    position: relative;
    z-index: 2;
}

.tracking-step.completed .tracking-container::before {
    background: linear-gradient(to bottom, #28a745 0%, #28a745 100%);
}

.tracking-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 1.2rem;
    position: relative;
    z-index: 3;
}

.tracking-step.completed .tracking-icon {
    background: #28a745;
    color: white;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #28a745;
}

.tracking-step.pending .tracking-icon {
    background: #f8f9fa;
    color: #6c757d;
    border: 3px solid #e9ecef;
}

.tracking-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.tracking-content p {
    margin-bottom: 5px;
    color: #6c757d;
}

.tracking-content small {
    font-size: 0.8rem;
}
</style>
@endsection
