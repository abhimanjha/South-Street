@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">🛒 Your Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($cart->items->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                @foreach($cart->items as $item)
                    <tr data-item-id="{{ $item->id }}">
                        <td>
                            <img src="{{ $item->product->primaryImage ? asset($item->product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}" alt="{{ $item->product->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                        </td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->product->price, 2) }}</td>
                        <td>₹{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-item" data-item-id="{{ $item->id }}">Remove</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <strong>Total: ₹<span id="cart-total">{{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 2) }}</span></strong>
            </div>
            <div>
                <a href="{{ route('home') }}" class="btn btn-secondary me-2">Continue Shopping</a>
                @auth
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buyNowModal">Buy Now</button>
                @else
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Buy Now</button>
                @endauth
            </div>
        </div>
    @else
        <div id="empty-cart">
            <p>Your cart is empty. Start shopping!</p>
        </div>
    @endif
</div>

<!-- Buy Now Modal -->
<div class="modal fade" id="buyNowModal" tabindex="-1" aria-labelledby="buyNowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyNowModalLabel">Complete Your Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(auth()->check())
                    <!-- Show checkout form directly -->
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Order Summary</h6>
                            <div class="border rounded p-3 mb-3">
                                @foreach($cart->items as $item)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->primaryImage ? asset($item->product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}"
                                                 alt="{{ $item->product->name }}"
                                                 style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                <br>
                                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                            </div>
                                        </div>
                                        <span>₹{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span>₹{{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Shipping:</span>
                                    <span>₹{{ $cart->items->sum(fn($i) => $i->product->price * $i->quantity) > 500 ? '0.00' : '50.00' }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>₹{{ number_format(($cart->items->sum(fn($i) => $i->product->price * $i->quantity) > 500 ? 0 : 50) + $cart->items->sum(fn($i) => $i->product->price * $i->quantity), 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h6>Payment Options</h6>
                            <form id="quickCheckoutForm" action="{{ route('checkout.place-order') }}" method="POST">
                                @csrf
                                <input type="hidden" name="address_id" value="{{ auth()->user()?->addresses->first()?->id ?? '' }}">

                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select name="payment_method" class="form-select" required id="payment_method_select">
                                        <option value="cod">Cash on Delivery</option>
                                        <option value="card">Credit/Debit Card</option>
                                        <option value="upi">UPI (QR Code & UPI Apps)</option>
                                        <option value="netbanking">Net Banking</option>
                                        <option value="wallet">Wallets</option>
                                    </select>
                                    <small class="text-muted">For UPI, you can scan QR code or use UPI apps like Google Pay, PhonePe, Paytm</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Delivery Address</label>
                                    <select name="address_id" class="form-select" required style="height: auto; min-height: 100px;">
                                        @forelse(auth()->user()?->addresses ?? collect() as $address)
                                            <option value="{{ $address->id }}" {{ $address->is_default ? 'selected' : '' }}>
                                                <strong>{{ $address->name }}</strong> ({{ $address->phone }})
                                                <br>{{ $address->street }}, {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                                                {{ $address->is_default ? '(Default)' : '' }}
                                            </option>
                                        @empty
                                            <option value="">No addresses available</option>
                                        @endforelse
                                    </select>
                                    @if(!auth()->check() || auth()->user()?->addresses->isEmpty())
                                        <small class="text-muted">No addresses found. <a href="{{ route('account.addresses') }}">Add address</a></small>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-credit-card me-2"></i>Place Order
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Show login prompt -->
                    <div class="text-center py-4">
                        <i class="fas fa-sign-in-alt text-primary" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Login Required</h5>
                        <p class="text-muted">Please login to complete your purchase</p>
                        <div class="mt-4">
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Error Message Display -->
<div id="error-message" class="alert alert-danger position-fixed" style="top: 20px; right: 20px; z-index: 9999; display: none; min-width: 300px;" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <span id="error-text"></span>
    <button type="button" class="btn-close" onclick="hideErrorMessage()"></button>
</div>

<!-- Include Payment Modal -->
@include('checkout.payment-modal')

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-sign-in-alt text-primary" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Please Login</h5>
                <p class="text-muted">You need to login to proceed with your purchase</p>
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle item removal with AJAX
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const row = this.closest('tr');

            if (confirm('Are you sure you want to remove this item from your cart?')) {
                fetch(`{{ url('/cart') }}/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        row.remove();

                        // Update cart total
                        document.getElementById('cart-total').textContent = parseFloat(data.cart_total).toFixed(2);

                        // Show success message
                        showToast(data.message, 'success');

                        // Check if cart is empty
                        const cartItems = document.getElementById('cart-items');
                        if (cartItems.children.length === 0) {
                            location.reload(); // Reload to show empty cart message
                        }
                    } else {
                        showToast('Failed to remove item', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred', 'error');
                });
            }
        });
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 3000);
}

// Handle quick checkout form submission
document.getElementById('quickCheckoutForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        redirect: 'manual'
    })
    .then(response => {
        if (response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // If not JSON, it's likely an HTML error page (e.g., login redirect)
                throw new Error('Authentication required. Please log in to continue.');
            }
        } else {
            // Handle HTTP error status codes
            if (response.status === 401) {
                throw new Error('Authentication required. Please log in to continue.');
            } else if (response.status === 422) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Validation error occurred.');
                });
            } else {
                // Try to extract error message from response
                return response.json().then(data => {
                    throw new Error(data.message || 'Server error occurred. Please try again.');
                }).catch(() => {
                    // If response is not JSON, throw generic error
                    throw new Error('Server error occurred. Please try again.');
                });
            }
        }
    })
    .then(data => {
        if (data.success) {
            // Check if it's an online payment method that requires Razorpay
            if (data.payment_method === 'card' || data.payment_method === 'upi' || 
                data.payment_method === 'netbanking' || data.payment_method === 'wallet' || 
                data.payment_method === 'emi') {
                // Initialize Razorpay directly with the order data from server
                if (data.razorpay_order_id && data.key && data.amount) {
                    initializeRazorpayPayment(data);
                } else {
                    // Fallback: show payment modal if data structure is different
                    showPaymentModal(data);
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Place Order';
            } else if (data.redirect) {
                // COD or other redirect cases
                window.location.href = data.redirect;
            } else {
                // Order placed successfully - redirect without alert
                window.location.href = '{{ route("home") }}';
            }
        } else {
            showErrorMessage(data.message || 'An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Place Order';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage(error.message || 'Network error occurred.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Place Order';
    });
});

// Function to show error message
function showErrorMessage(message) {
    const errorDiv = document.getElementById('error-message');
    if (errorDiv) {
        const errorText = document.getElementById('error-text');
        errorText.textContent = message;
        errorDiv.style.display = 'block';

        // Auto-hide after 10 seconds
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 10000);
    } else {
        alert(message);
    }
}

// Function to hide error message
function hideErrorMessage() {
    const errorDiv = document.getElementById('error-message');
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
}

// Initialize Razorpay payment
function initializeRazorpayPayment(data) {
    // Check if Razorpay is loaded
    if (typeof Razorpay === 'undefined') {
        showErrorMessage('Razorpay SDK not loaded. Please refresh the page and try again.');
        return;
    }

    // Build options based on payment method
    const options = {
        key: data.key,
        amount: data.amount,
        currency: data.currency || 'INR',
        name: data.name || '{{ config("app.name") }}',
        description: data.description || `Order #${data.order_number || ''}`,
        order_id: data.razorpay_order_id,
        prefill: data.prefill || {
            name: '{{ auth()->user()?->name ?? "" }}',
            email: '{{ auth()->user()?->email ?? "" }}',
            contact: '{{ auth()->user()?->phone ?? auth()->user()?->addresses?->first()?->phone ?? "" }}'
        },
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
        },
        notes: {
            order_id: data.order_id || '',
            order_number: data.order_number || ''
        }
    };

    // Configure payment method based on selection
    // For UPI, Razorpay will automatically show UPI options including QR code if enabled in account
    if (data.payment_method === 'upi') {
        // Explicitly enable UPI - this will show UPI apps and QR code
        // Note: QR code will only appear if UPI QR code is enabled in your Razorpay dashboard
        // Go to: Razorpay Dashboard > Settings > Payment Methods > Enable UPI QR Code
        options.method = {
            upi: true
        };
        console.log('UPI payment method selected - QR code should be available if enabled in Razorpay account');
    } else if (data.payment_method === 'card') {
        options.method = {
            card: true
        };
    } else if (data.payment_method === 'netbanking') {
        options.method = {
            netbanking: true
        };
    } else if (data.payment_method === 'wallet') {
        options.method = {
            wallet: true
        };
    } else if (data.payment_method === 'emi') {
        options.method = {
            emi: true
        };
    }
    // If no specific method, Razorpay will show all enabled payment methods

    console.log('Initializing Razorpay with options:', {
        payment_method: data.payment_method,
        amount: data.amount,
        order_id: data.razorpay_order_id,
        method_config: options.method
    });

    try {
        const rzp = new Razorpay(options);
        rzp.on('payment.failed', function (response) {
            console.error('Payment failed:', response.error);
            showErrorMessage('Payment failed: ' + (response.error.description || 'Please try again.'));
        });
        rzp.open();
    } catch (error) {
        console.error('Razorpay initialization error:', error);
        showErrorMessage('Failed to initialize payment. Please try again.');
    }
}

// Verify payment
function verifyPayment(response, orderId) {
    // Show loading message
    showToast('Verifying payment...', 'info');

    fetch('{{ route("razorpay.verify") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
            showToast('Payment successful! Redirecting...', 'success');

            // Redirect after a short delay
            setTimeout(() => {
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    window.location.href = '{{ route("home") }}';
                }
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

// Simple toast function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 3000);
}
</script>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection
