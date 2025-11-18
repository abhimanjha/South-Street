<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="fas fa-credit-card me-2"></i>Complete Your Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Spinner -->
                <div id="modal-loading" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Preparing payment...</p>
                </div>

                <!-- Order Summary -->
                <div id="order-summary">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h6 class="fw-bold">Order Summary</h6>
                            <div id="order-items" class="mb-3">
                                <!-- Order items will be loaded here -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Order Total:</span>
                                        <span id="modal-total" class="fw-bold text-primary fs-5">₹0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Payment Method:</span>
                                        <span id="modal-payment-method" class="text-muted">Razorpay</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div id="payment-form" style="display: none;">
                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-lg w-100" id="confirm-pay-btn">
                            <i class="fas fa-lock me-2"></i>Confirm & Pay ₹<span id="pay-amount">0.00</span>
                        </button>
                        <p class="text-muted mt-2 small">
                            <i class="fas fa-shield-alt me-1"></i>Secure payment powered by Razorpay
                        </p>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="payment-error" class="alert alert-danger" style="display: none;" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="error-message"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;
let razorpayOrderData = null;

// Function to show payment modal
function showPaymentModal(orderData) {
    currentOrderId = orderData.order_id;

    // Update modal content
    document.getElementById('modal-total').textContent = '₹' + parseFloat(orderData.total).toFixed(2);
    document.getElementById('pay-amount').textContent = parseFloat(orderData.total).toFixed(2);
    document.getElementById('modal-payment-method').textContent = orderData.payment_method.charAt(0).toUpperCase() + orderData.payment_method.slice(1);

    // Load order items
    loadOrderItems(orderData.order_id);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();

    // Show payment form after a brief delay
    setTimeout(() => {
        document.getElementById('order-summary').style.display = 'block';
        document.getElementById('payment-form').style.display = 'block';
    }, 500);
}

// Function to load order items
function loadOrderItems(orderId) {
    // For now, we'll show a simple message. In a real implementation,
    // you might want to fetch order details via AJAX
    const orderItemsContainer = document.getElementById('order-items');
    orderItemsContainer.innerHTML = `
        <div class="text-muted">
            <small>Order #<span class="fw-bold">${orderId}</span></small>
        </div>
    `;
}

// Handle confirm payment button
document.getElementById('confirm-pay-btn').addEventListener('click', function() {
    if (!currentOrderId) {
        showError('Order ID not found. Please try again.');
        return;
    }

    // Show loading
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

    // Create Razorpay order
    fetch('{{ route("razorpay.createOrder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            order_id: currentOrderId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            razorpayOrderData = data;
            initializeRazorpayPayment(data);
        } else {
            showError(data.message || 'Failed to create payment order.');
            resetPaymentButton();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred. Please try again.');
        resetPaymentButton();
    });
});

// Initialize Razorpay payment
function initializeRazorpayPayment(data) {
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
            verifyPayment(response);
        },
        modal: {
            ondismiss: function() {
                // Handle payment cancellation
                resetPaymentButton();
                showError('Payment was cancelled.');
            }
        },
        theme: {
            color: '#007bff'
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
}

// Verify payment
function verifyPayment(response) {
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
            order_id: currentOrderId
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Close modal and redirect
            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
            modal.hide();

            // Show success message
            showToast('Payment successful! Redirecting...', 'success');

            // Redirect after a short delay
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1500);
        } else {
            showError(result.message || 'Payment verification failed.');
            resetPaymentButton();
        }
    })
    .catch(error => {
        console.error('Verification error:', error);
        showError('Payment verification failed. Please contact support.');
        resetPaymentButton();
    });
}

// Utility functions
function resetPaymentButton() {
    const btn = document.getElementById('confirm-pay-btn');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm & Pay ₹<span id="pay-amount">0.00</span>';
}

function showError(message) {
    const errorDiv = document.getElementById('payment-error');
    document.getElementById('error-message').textContent = message;
    errorDiv.style.display = 'block';

    // Hide after 5 seconds
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}

function showToast(message, type = 'info') {
    // Simple toast implementation - you can replace with a proper toast library
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}`;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Reset modal when closed
document.getElementById('paymentModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('order-summary').style.display = 'none';
    document.getElementById('payment-form').style.display = 'none';
    document.getElementById('payment-error').style.display = 'none';
    resetPaymentButton();
    currentOrderId = null;
    razorpayOrderData = null;
});
</script>
