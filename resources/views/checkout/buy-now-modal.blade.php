<!-- Buy Now Modal -->
<div class="modal fade" id="buyNowModal" tabindex="-1" aria-labelledby="buyNowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="buyNowModalLabel">
                    <i class="fas fa-bolt me-2"></i>Buy Now - Quick Checkout
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Spinner -->
                <div id="buy-now-loading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Preparing your order...</p>
                </div>

                <!-- Main Content -->
                <div id="buy-now-content">
                    <div class="row">
                        <!-- Product Details -->
                        <div class="col-lg-6">
                            <div class="product-summary-card">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-shopping-bag me-2 text-success"></i>Product Details
                                </h6>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-4">
                                                <img id="modal-product-image" src="" alt="Product" class="img-fluid rounded" style="max-height: 100px;">
                                            </div>
                                            <div class="col-8">
                                                <h6 id="modal-product-name" class="card-title mb-2"></h6>
                                                <p id="modal-product-variant" class="text-muted small mb-2"></p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-success" id="modal-product-price"></span>
                                                    <span class="badge bg-light text-dark" id="modal-product-quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity Adjustment -->
                            <div class="quantity-section mt-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-hashtag me-2 text-success"></i>Quantity
                                </h6>
                                <div class="d-flex align-items-center justify-content-center">
                                    <button class="btn btn-outline-secondary btn-sm" id="decrease-qty-modal" type="button">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="form-control text-center mx-3" id="modal-quantity" value="1" min="1" style="width: 80px;">
                                    <button class="btn btn-outline-secondary btn-sm" id="increase-qty-modal" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery & Payment -->
                        <div class="col-lg-6">
                            <!-- Delivery Address -->
                            <div class="address-section mb-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-map-marker-alt me-2 text-success"></i>Delivery Address
                                </h6>
                                <div id="address-list">
                                    <!-- Addresses will be loaded here -->
                                </div>
                                <button class="btn btn-outline-primary btn-sm mt-2" id="add-new-address-btn">
                                    <i class="fas fa-plus me-2"></i>Add New Address
                                </button>
                            </div>

                            <!-- Order Summary -->
                            <div class="order-summary-section">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-receipt me-2 text-success"></i>Order Summary
                                </h6>
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span id="modal-subtotal">₹0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Shipping:</span>
                                            <span id="modal-shipping">₹0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax (18%):</span>
                                            <span id="modal-tax">₹0.00</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold fs-5">
                                            <span>Total:</span>
                                            <span id="modal-total" class="text-success">₹0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="payment-section mt-4 pt-4 border-top">
                        <div class="text-center">
                            <button type="button" class="btn btn-success btn-lg px-5" id="proceed-to-payment-btn">
                                <i class="fas fa-credit-card me-2"></i>Proceed to Payment
                            </button>
                            <p class="text-muted mt-2 small">
                                <i class="fas fa-shield-alt me-1"></i>Secure payment powered by Razorpay
                            </p>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="buy-now-error" class="alert alert-danger mt-3" style="display: none;" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="buy-now-error-message"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let buyNowProductData = null;
let selectedAddressId = null;

// Function to show Buy Now modal
function showBuyNowModal(productData) {
    buyNowProductData = productData;

    // Update product details
    document.getElementById('modal-product-image').src = productData.image || '{{ asset("imgs/placeholder.jpg") }}';
    document.getElementById('modal-product-name').textContent = productData.name;
    document.getElementById('modal-product-price').textContent = '₹' + parseFloat(productData.price).toFixed(2);
    document.getElementById('modal-product-quantity').textContent = 'Qty: ' + productData.quantity;
    document.getElementById('modal-quantity').value = productData.quantity;

    if (productData.variant_details) {
        document.getElementById('modal-product-variant').textContent = productData.variant_details;
    } else {
        document.getElementById('modal-product-variant').textContent = '';
    }

    // Load user addresses
    loadUserAddresses();

    // Calculate totals
    calculateTotals();

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('buyNowModal'));
    modal.show();
}

// Function to load user addresses
function loadUserAddresses() {
    fetch('{{ route("account.addresses.index") }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const addressList = document.getElementById('address-list');
        if (data.addresses && data.addresses.length > 0) {
            addressList.innerHTML = data.addresses.map(address => `
                <div class="address-card border rounded p-3 mb-2 ${address.is_default ? 'border-success bg-light' : ''}" data-address-id="${address.id}">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selected_address" value="${address.id}" id="address-${address.id}" ${address.is_default ? 'checked' : ''}>
                        <label class="form-check-label w-100" for="address-${address.id}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>${address.name}</strong>
                                    <br><small class="text-muted">${address.street}, ${address.city}, ${address.state} - ${address.pincode}</small>
                                    <br><small class="text-muted">Phone: ${address.phone}</small>
                                </div>
                                ${address.is_default ? '<span class="badge bg-success">Default</span>' : ''}
                            </div>
                        </label>
                    </div>
                </div>
            `).join('');

            // Set default selected address
            const defaultAddress = data.addresses.find(addr => addr.is_default);
            selectedAddressId = defaultAddress ? defaultAddress.id : data.addresses[0].id;

            // Add event listeners for address selection
            document.querySelectorAll('input[name="selected_address"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedAddressId = this.value;
                });
            });
        } else {
            addressList.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                    <p>No delivery addresses found.</p>
                    <p>Please add an address to continue.</p>
                </div>
            `;
            selectedAddressId = null;
        }
    })
    .catch(error => {
        console.error('Error loading addresses:', error);
        showBuyNowError('Failed to load addresses. Please try again.');
    });
}

// Function to calculate totals
function calculateTotals() {
    const quantity = parseInt(document.getElementById('modal-quantity').value) || 1;
    const price = parseFloat(buyNowProductData.price);
    const subtotal = price * quantity;
    const shipping = subtotal > 500 ? 0 : 50;
    const tax = subtotal * 0.18;
    const total = subtotal + shipping + tax;

    document.getElementById('modal-subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('modal-shipping').textContent = shipping === 0 ? 'FREE' : '₹' + shipping.toFixed(2);
    document.getElementById('modal-tax').textContent = '₹' + tax.toFixed(2);
    document.getElementById('modal-total').textContent = '₹' + total.toFixed(2);
}

// Quantity adjustment handlers
document.getElementById('decrease-qty-modal').addEventListener('click', function() {
    const qtyInput = document.getElementById('modal-quantity');
    const currentValue = parseInt(qtyInput.value);
    if (currentValue > 1) {
        qtyInput.value = currentValue - 1;
        buyNowProductData.quantity = currentValue - 1;
        document.getElementById('modal-product-quantity').textContent = 'Qty: ' + qtyInput.value;
        calculateTotals();
    }
});

document.getElementById('increase-qty-modal').addEventListener('click', function() {
    const qtyInput = document.getElementById('modal-quantity');
    const currentValue = parseInt(qtyInput.value);
    const maxValue = parseInt(qtyInput.max) || 99;
    if (currentValue < maxValue) {
        qtyInput.value = currentValue + 1;
        buyNowProductData.quantity = currentValue + 1;
        document.getElementById('modal-product-quantity').textContent = 'Qty: ' + qtyInput.value;
        calculateTotals();
    }
});

document.getElementById('modal-quantity').addEventListener('change', function() {
    const value = parseInt(this.value);
    if (value < 1) this.value = 1;
    buyNowProductData.quantity = parseInt(this.value);
    document.getElementById('modal-product-quantity').textContent = 'Qty: ' + this.value;
    calculateTotals();
});

// Add new address button
document.getElementById('add-new-address-btn').addEventListener('click', function() {
    window.location.href = '{{ route("account.addresses.create") }}';
});

// Proceed to payment
document.getElementById('proceed-to-payment-btn').addEventListener('click', function() {
    if (!selectedAddressId) {
        showBuyNowError('Please select a delivery address.');
        return;
    }

    if (!buyNowProductData) {
        showBuyNowError('Product data not found. Please try again.');
        return;
    }

    // Show loading
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    document.getElementById('buy-now-loading').style.display = 'block';
    document.getElementById('buy-now-content').style.display = 'none';

    // Create Buy Now order
    fetch('{{ route("razorpay.createBuyNowOrder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: buyNowProductData.id,
            quantity: buyNowProductData.quantity,
            variant_id: buyNowProductData.variant_id,
            address_id: selectedAddressId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            initializeBuyNowPayment(data);
        } else {
            showBuyNowError(data.message || 'Failed to create order. Please try again.');
            resetPaymentButton();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showBuyNowError('An error occurred. Please try again.');
        resetPaymentButton();
    });
});

// Initialize Razorpay payment for Buy Now
function initializeBuyNowPayment(data) {
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
            verifyBuyNowPayment(response, data.order_id);
        },
        modal: {
            ondismiss: function() {
                // Handle payment cancellation
                resetPaymentButton();
                showBuyNowError('Payment was cancelled.');
            }
        },
        theme: {
            color: '#28a745'
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
}

// Verify Buy Now payment
function verifyBuyNowPayment(response, orderId) {
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
            // Close modal and redirect
            const modal = bootstrap.Modal.getInstance(document.getElementById('buyNowModal'));
            modal.hide();

            // Show success message
            showToast('Payment successful! Redirecting...', 'success');

            // Redirect after a short delay
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1500);
        } else {
            showBuyNowError(result.message || 'Payment verification failed.');
            resetPaymentButton();
        }
    })
    .catch(error => {
        console.error('Verification error:', error);
        showBuyNowError('Payment verification failed. Please contact support.');
        resetPaymentButton();
    });
}

// Utility functions
function resetPaymentButton() {
    const btn = document.getElementById('proceed-to-payment-btn');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Proceed to Payment';
    document.getElementById('buy-now-loading').style.display = 'none';
    document.getElementById('buy-now-content').style.display = 'block';
}

function showBuyNowError(message) {
    const errorDiv = document.getElementById('buy-now-error');
    document.getElementById('buy-now-error-message').textContent = message;
    errorDiv.style.display = 'block';

    // Hide after 5 seconds
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}

function showToast(message, type = 'info') {
    // Simple toast implementation
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
document.getElementById('buyNowModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('buy-now-error').style.display = 'none';
    resetPaymentButton();
    buyNowProductData = null;
    selectedAddressId = null;
});
</script>

<style>
.address-card:hover {
    border-color: #28a745 !important;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.1);
}

.address-card.selected {
    border-color: #28a745 !important;
    background-color: #f8fff9 !important;
}

.product-summary-card .card {
    transition: transform 0.2s ease;
}

.product-summary-card .card:hover {
    transform: translateY(-2px);
}

.quantity-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.payment-section {
    background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);
    border-radius: 8px;
}

@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem;
    }

    .address-card {
        margin-bottom: 1rem;
    }
}
</style>
