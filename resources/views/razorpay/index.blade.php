@extends('layouts.app')

@section('title', 'Razorpay Payment')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Razorpay Payment Gateway
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Order Summary -->
                    <div class="order-summary mb-4">
                        <h5 class="text-muted mb-3">Order Summary</h5>
                        <div class="row">
                            <div class="col-sm-6">
                                <p><strong>Order ID:</strong> <span id="order-id">#ORD-0001</span></p>
                                <p><strong>Amount:</strong> <span id="order-amount" class="text-success fw-bold">₹1,000.00</span></p>
                            </div>
                            <div class="col-sm-6">
                                <p><strong>Customer:</strong> <span id="customer-name">John Doe</span></p>
                                <p><strong>Email:</strong> <span id="customer-email">john@example.com</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <div class="text-center">
                        <button id="rzp-button1" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-lock me-2"></i>Pay Now with Razorpay
                        </button>
                        <p class="text-muted mt-2 small">
                            <i class="fas fa-shield-alt me-1"></i>Secure payment powered by Razorpay
                        </p>
                    </div>

                    <!-- Payment Status -->
                    <div id="payment-status" class="mt-4" style="display: none;">
                        <div class="alert" role="alert">
                            <span id="status-message"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                        <h6>Secure Payment</h6>
                        <p class="small text-muted">256-bit SSL encryption</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-mobile-alt fa-2x text-primary mb-2"></i>
                        <h6>Mobile Friendly</h6>
                        <p class="small text-muted">Works on all devices</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                        <h6>Instant Processing</h6>
                        <p class="small text-muted">Real-time payment confirmation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Razorpay configuration
    const options = {
        "key": "{{ config('services.razorpay.key') }}", // Enter the Key ID generated from the Dashboard
        "amount": "100000", // Amount is in currency subunits. Default currency is INR. Hence, 100000 refers to 1000 INR
        "currency": "INR",
        "name": "{{ config('app.name') }}",
        "description": "Test Transaction",
        "image": "https://example.com/your_logo",
        "order_id": "order_9A33XWu170gUtm", // This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "callback_url": "{{ route('razorpay.verify') }}",
        "prefill": {
            "name": "John Doe",
            "email": "john@example.com",
            "contact": "9999999999"
        },
        "notes": {
            "address": "Razorpay Corporate Office"
        },
        "theme": {
            "color": "#3399cc"
        },
        "handler": function (response) {
            // Handle successful payment
            showPaymentStatus('Payment successful! Payment ID: ' + response.razorpay_payment_id, 'success');

            // You can redirect to success page or perform other actions here
            setTimeout(function() {
                window.location.href = '{{ route("home") }}';
            }, 3000);
        },
        "modal": {
            "ondismiss": function() {
                showPaymentStatus('Payment cancelled by user.', 'warning');
            }
        }
    };

    const rzp1 = new Razorpay(options);

    document.getElementById('rzp-button1').onclick = function(e) {
        rzp1.open();
        e.preventDefault();
    };

    function showPaymentStatus(message, type) {
        const statusDiv = document.getElementById('payment-status');
        const statusMessage = document.getElementById('status-message');
        const alertDiv = statusDiv.querySelector('.alert');

        statusMessage.textContent = message;
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'}`;
        statusDiv.style.display = 'block';

        // Scroll to status
        statusDiv.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
}

.order-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.alert {
    border-radius: 8px;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    .card-body {
        padding: 20px;
    }
}
</style>
@endsection
