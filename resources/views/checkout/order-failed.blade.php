@extends('layouts.app')

@section('title', 'Payment Failed - ' . config('app.name'))

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <!-- Failed Icon -->
                    <div class="failed-icon mb-4">
                        <i class="fas fa-times-circle text-danger" style="font-size: 4rem;"></i>
                    </div>

                    <!-- Failed Message -->
                    <h2 class="text-danger mb-3">Payment Failed</h2>
                    <p class="lead text-muted mb-4">We couldn't process your payment. Please try again.</p>

                    <!-- Error Details -->
                    <div class="alert alert-danger mb-4" role="alert">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>What happened?
                        </h6>
                        <p class="mb-0">
                            Your payment could not be completed due to one of the following reasons:
                        </p>
                        <ul class="text-start mt-2 mb-0">
                            <li>Insufficient funds in your account</li>
                            <li>Card details entered incorrectly</li>
                            <li>Bank server issues</li>
                            <li>Payment gateway timeout</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-primary me-2 mb-2">
                            <i class="fas fa-redo me-2"></i>Try Again
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary mb-2">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>

                    <!-- Support Info -->
                    <div class="support-info mt-4">
                        <p class="text-muted mb-2">Need help? Contact our support team:</p>
                        <div class="d-flex justify-content-center">
                            <a href="mailto:support@garmeva.com" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-envelope me-1"></i>Email Support
                            </a>
                            <a href="tel:+919876543210" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-phone me-1"></i>Call Support
                            </a>
                        </div>
                    </div>

                    <!-- Order Info (if available) -->
                    @if(isset($order) && $order)
                    <div class="order-info bg-light p-3 rounded mt-4">
                        <h6 class="mb-2">Order Information</h6>
                        <p class="mb-1"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                        <p class="mb-1"><strong>Amount:</strong> ₹{{ number_format($order->total, 2) }}</p>
                        <p class="mb-0"><strong>Status:</strong>
                            <span class="badge bg-warning">Payment Pending</span>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.failed-icon {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.support-info a {
    text-decoration: none;
}

@media (max-width: 576px) {
    .action-buttons .btn {
        width: 100%;
    }

    .support-info .btn {
        width: 48%;
    }
}
</style>
@endsection
