@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<style>
/* Disable card hover jump effect on checkout page */
.card:hover {
    transform: none !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
}
</style>
<div class="container my-5">
    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    @foreach($cart->items as $item)
                    <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                        <img src="{{ $item->product->primaryImage ? asset($item->product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}"
                             alt="{{ $item->product->name }}"
                             class="img-fluid rounded me-3"
                             style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            @if($item->variant)
                            <small class="text-muted">{{ $item->variant->size }} / {{ $item->variant->color }}</small>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted">Qty: {{ $item->quantity }}</span>
                                <span class="fw-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Price Details</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($discount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount:</span>
                        <span>-₹{{ number_format($discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>₹{{ number_format($shippingCharge, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (18%):</span>
                        <span>₹{{ number_format($tax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span>₹{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.place-order') }}" method="POST" id="checkout-form">
                @csrf

                <!-- Delivery Address -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Delivery Address</h5>
                    </div>
                    <div class="card-body">
                        @if($addresses->count() > 0)
                            @foreach($addresses as $address)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="address_id" value="{{ $address->id }}" id="address{{ $address->id }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="form-check-label" for="address{{ $address->id }}">
                                    <strong>{{ $address->name }}</strong><br>
                                    {{ $address->street_address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}<br>
                                    <small class="text-muted">{{ $address->phone }}</small>
                                </label>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted mb-3">No addresses found. Please add an address.</p>
                            <a href="{{ route('account.addresses') }}" class="btn btn-outline-primary btn-sm">Add Address</a>
                        @endif
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                            <label class="form-check-label" for="cod">
                                <strong>Cash on Delivery</strong><br>
                                <small class="text-muted">Pay when you receive your order</small>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" value="card" id="card">
                            <label class="form-check-label" for="card">
                                <strong>Razorpay</strong><br>
                                <small class="text-muted">Credit/Debit Card, UPI, Net Banking, Wallets</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Notes (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" name="notes" rows="3" placeholder="Any special instructions for delivery..."></textarea>
                    </div>
                </div>

                <!-- Place Order Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg" id="place-order-btn">
                        <i class="fas fa-credit-card me-2"></i>Place Order (₹{{ number_format($total, 2) }})
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
// Handle form submission
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    const submitBtn = document.getElementById('place-order-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

    // Prepare form data
    const formData = new FormData(this);
    formData.append('_token', '{{ csrf_token() }}');

    // Send AJAX request
    fetch('{{ route("checkout.place-order") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Backend response:', data); // Debug: Show full backend response

        if (data.success) {
            console.log('Order placement successful, payment method:', data.payment_method);

            if (data.payment_method === 'card') {
                console.log('Initiating Razorpay payment...');
                // Directly initialize Razorpay with data from backend
                const options = {
                    key: data.key,
                    amount: data.amount,
                    currency: data.currency,
                    name: data.name,
                    description: data.description,
                    order_id: data.razorpay_order_id,
                    prefill: data.prefill,
                    handler: function (response) {
                        // Handle successful payment
                        verifyPayment(response, data.order_id);
                    },
                    modal: {
                        ondismiss: function() {
                            // Handle payment cancellation
                            showErrorMessage('Payment was cancelled.');
                        }
                    },
                    theme: {
                        color: '#007bff'
                    }
                };

                const rzp = new Razorpay(options);
                rzp.open();
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Place Order (₹{{ number_format($total, 2) }})';
            } else if (data.redirect) {
                console.log('Redirecting to:', data.redirect);
                // COD or other redirect cases
                window.location.href = data.redirect;
            } else {
                console.log('Order placed successfully, redirecting to home');
                // Order placed successfully - redirect without alert
                window.location.href = '{{ route("home") }}';
            }
        } else {
            console.error('Order placement failed:', data.message);
            console.error('Full error response:', data);

            // Show error in a visible way without alert
            showErrorMessage(data.message || 'An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Place Order (₹{{ number_format($total, 2) }})';
        }
    })
    .catch(error => {
        console.error('Network error:', error);
        console.error('Error details:', error.message);

        // Show error in a visible way without alert
        showErrorMessage('Network error: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Place Order (₹{{ number_format($total, 2) }})';
    });
});
</script>

<!-- Error Message Display -->
<div id="error-message" class="alert alert-danger position-fixed" style="top: 20px; right: 20px; z-index: 9999; display: none; min-width: 300px;" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <span id="error-text"></span>
    <button type="button" class="btn-close" onclick="hideErrorMessage()"></button>
</div>



// Function to verify payment
function verifyPayment(response, orderId) {
    fetch('{{ route("razorpay.verify") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_order_id: response.razorpay_order_id,
            razorpay_signature: response.razorpay_signature,
            order_id: orderId
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Show success message
            showSuccessMessage('Payment successful! Redirecting...');

            // Redirect after a short delay
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1500);
        } else {
            showErrorMessage(result.message || 'Payment verification failed.');
        }
    })
    .catch(error => {
        console.error('Verification error:', error);
        showErrorMessage('Payment verification failed. Please contact support.');
    });
}

// Function to show error message
function showErrorMessage(message) {
    const errorDiv = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    errorText.textContent = message;
    errorDiv.style.display = 'block';

    // Auto-hide after 10 seconds
    setTimeout(() => {
        hideErrorMessage();
    }, 10000);
}

// Function to hide error message
function hideErrorMessage() {
    document.getElementById('error-message').style.display = 'none';
}

// Function to show success message
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'alert alert-success position-fixed';
    successDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    successDiv.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;

    document.body.appendChild(successDiv);

    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}
</script>
@endsection
