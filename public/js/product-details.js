/**
 * Product Details Page JavaScript
 * Handles image gallery, variants, quantity, cart, wishlist, and reviews
 */

const ProductDetails = {
    config: {
        productId: null,
        maxQuantity: 1,
        csrfToken: null
    },

    init(options) {
        this.config = { ...this.config, ...options };
        
        // Debug: Check if CSRF token is available
        if (!this.config.csrfToken) {
            console.error('CSRF token not provided');
            this.showNotification('Security token missing. Please refresh the page.', 'error');
            return;
        }
        
        this.initImageGallery();
        this.initQuantityControls();
        this.initVariantSelection();
        this.initCartActions();
        this.initWishlistActions();
        this.initReviewForm();
        this.initImageZoom();
    },

    // Image Gallery
    initImageGallery() {
        const mainImage = document.getElementById('main-product-image');
        const thumbnails = document.querySelectorAll('.thumbnail-item');

        if (!mainImage || thumbnails.length === 0) return;

        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                thumbnail.classList.add('active');
                
                // Update main image with smooth transition
                mainImage.style.opacity = '0.5';
                setTimeout(() => {
                    mainImage.src = thumbnail.dataset.image;
                    mainImage.style.opacity = '1';
                }, 150);
            });
        });

        // Set first thumbnail as active if none is active
        if (!document.querySelector('.thumbnail-item.active') && thumbnails.length > 0) {
            thumbnails[0].classList.add('active');
        }
    },

    // Image Zoom
    initImageZoom() {
        const mainImage = document.getElementById('main-product-image');
        const zoomOverlay = document.querySelector('.image-zoom-overlay');

        if (!mainImage || !zoomOverlay) return;

        zoomOverlay.addEventListener('click', () => {
            this.openImageModal(mainImage.src);
        });

        mainImage.addEventListener('click', () => {
            this.openImageModal(mainImage.src);
        });
    },

    openImageModal(imageSrc) {
        // Create modal for image zoom
        const modal = document.createElement('div');
        modal.className = 'image-zoom-modal';
        modal.innerHTML = `
            <div class="zoom-modal-backdrop">
                <div class="zoom-modal-content">
                    <button class="zoom-modal-close">&times;</button>
                    <img src="${imageSrc}" alt="Product Image" class="zoom-modal-image">
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        // Close modal events
        const closeBtn = modal.querySelector('.zoom-modal-close');
        const backdrop = modal.querySelector('.zoom-modal-backdrop');

        closeBtn.addEventListener('click', () => this.closeImageModal(modal));
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) this.closeImageModal(modal);
        });

        // ESC key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.closeImageModal(modal);
        });
    },

    closeImageModal(modal) {
        document.body.style.overflow = '';
        modal.remove();
    },

    // Quantity Controls
    initQuantityControls() {
        const decreaseBtn = document.getElementById('decrease-qty');
        const increaseBtn = document.getElementById('increase-qty');
        const quantityInput = document.getElementById('quantity');

        if (!decreaseBtn || !increaseBtn || !quantityInput) return;

        decreaseBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                this.updateQuantityState();
            }
        });

        increaseBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < this.config.maxQuantity) {
                quantityInput.value = currentValue + 1;
                this.updateQuantityState();
            }
        });

        quantityInput.addEventListener('change', () => {
            this.validateQuantity();
        });

        this.updateQuantityState();
    },

    updateQuantityState() {
        const decreaseBtn = document.getElementById('decrease-qty');
        const increaseBtn = document.getElementById('increase-qty');
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);

        decreaseBtn.disabled = currentValue <= 1;
        increaseBtn.disabled = currentValue >= this.config.maxQuantity;

        decreaseBtn.style.opacity = currentValue <= 1 ? '0.5' : '1';
        increaseBtn.style.opacity = currentValue >= this.config.maxQuantity ? '0.5' : '1';
    },

    validateQuantity() {
        const quantityInput = document.getElementById('quantity');
        let value = parseInt(quantityInput.value);

        if (isNaN(value) || value < 1) {
            value = 1;
        } else if (value > this.config.maxQuantity) {
            value = this.config.maxQuantity;
        }

        quantityInput.value = value;
        this.updateQuantityState();
    },

    // Variant Selection
    initVariantSelection() {
        // Size options
        const sizeOptions = document.querySelectorAll('.size-option');
        sizeOptions.forEach(option => {
            option.addEventListener('click', () => {
                sizeOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                this.updateVariantAvailability();
            });
        });

        // Color options
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            option.addEventListener('click', () => {
                colorOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                this.updateVariantAvailability();
            });
        });
    },

    updateVariantAvailability() {
        // This would typically check variant availability and update UI
        // For now, we'll just ensure the add to cart button is enabled
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        if (addToCartBtn) {
            addToCartBtn.disabled = false;
        }
    },

    getSelectedVariant() {
        const selectedSize = document.querySelector('.size-option.active')?.dataset.size;
        const selectedColor = document.querySelector('.color-option.active')?.dataset.color;
        
        return {
            size: selectedSize || null,
            color: selectedColor || null
        };
    },

    // Cart Actions
    initCartActions() {
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        if (!addToCartBtn) return;

        addToCartBtn.addEventListener('click', () => {
            this.addToCart();
        });
    },

    async addToCart() {
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const quantity = parseInt(document.getElementById('quantity').value);
        const variant = this.getSelectedVariant();

        // Disable button and show loading
        addToCartBtn.disabled = true;
        const originalText = addToCartBtn.innerHTML;
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken
                },
                body: JSON.stringify({
                    product_id: this.config.productId,
                    quantity: quantity,
                    size: variant.size,
                    color: variant.color
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Product added to cart successfully!', 'success');
                this.updateCartCount(data.cart_count);
                
                // Brief success state
                addToCartBtn.innerHTML = '<i class="fas fa-check"></i> Added!';
                setTimeout(() => {
                    addToCartBtn.innerHTML = originalText;
                    addToCartBtn.disabled = false;
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to add to cart');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification(error.message || 'Error adding to cart', 'error');
            
            addToCartBtn.innerHTML = originalText;
            addToCartBtn.disabled = false;
        }
    },

    // Wishlist Actions
    initWishlistActions() {
        const wishlistBtn = document.getElementById('add-to-wishlist-btn');
        if (!wishlistBtn) return;

        wishlistBtn.addEventListener('click', () => {
            this.toggleWishlist();
        });
    },

    async toggleWishlist() {
        const wishlistBtn = document.getElementById('add-to-wishlist-btn');
        
        // Check if user is logged in
        if (!document.querySelector('meta[name="user-id"]')) {
            this.showNotification('Please login to add items to wishlist', 'warning');
            return;
        }

        const originalText = wishlistBtn.innerHTML;
        wishlistBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        wishlistBtn.disabled = true;

        try {
            const response = await fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken
                },
                body: JSON.stringify({
                    product_id: this.config.productId
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.message, 'success');
                
                // Update button state
                if (data.added) {
                    wishlistBtn.innerHTML = '<i class="fas fa-heart"></i> In Wishlist';
                    wishlistBtn.classList.add('in-wishlist');
                } else {
                    wishlistBtn.innerHTML = '<i class="far fa-heart"></i> Add to Wishlist';
                    wishlistBtn.classList.remove('in-wishlist');
                }
            } else {
                throw new Error(data.message || 'Failed to update wishlist');
            }
        } catch (error) {
            console.error('Error updating wishlist:', error);
            this.showNotification(error.message || 'Error updating wishlist', 'error');
            wishlistBtn.innerHTML = originalText;
        }

        wishlistBtn.disabled = false;
    },

    // Review Form
    initReviewForm() {
        const reviewForm = document.getElementById('review-form');
        if (!reviewForm) return;

        reviewForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitReview();
        });

        // Test connection button
        const testBtn = document.querySelector('.test-connection');
        if (testBtn) {
            testBtn.addEventListener('click', () => {
                this.testConnection();
            });
        }

        // Star rating interaction
        this.initStarRating();
    },

    async testConnection() {
        const testBtn = document.querySelector('.test-connection');
        const originalText = testBtn.innerHTML;
        testBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
        testBtn.disabled = true;

        try {
            const response = await fetch('/reviews/test', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            });

            if (!response.ok) {
                const text = await response.text();
                throw new Error(`HTTP ${response.status}: ${text.substring(0, 100)}`);
            }

            const data = await response.json();
            this.showNotification(`Connection test successful! User: ${data.user}, CSRF: ${data.csrf_token}`, 'success');
            console.log('Test response:', data);
        } catch (error) {
            console.error('Connection test failed:', error);
            this.showNotification(`Connection test failed: ${error.message}`, 'error');
        }

        testBtn.innerHTML = originalText;
        testBtn.disabled = false;
    },

    initStarRating() {
        const ratingInputs = document.querySelectorAll('.rating-input input[type="radio"]');
        const starLabels = document.querySelectorAll('.star-label');

        starLabels.forEach((label, index) => {
            label.addEventListener('mouseenter', () => {
                this.highlightStars(5 - index);
            });

            label.addEventListener('mouseleave', () => {
                this.resetStarHighlight();
            });

            label.addEventListener('click', () => {
                ratingInputs[index].checked = true;
                this.updateSelectedRating(5 - index);
            });
        });
    },

    highlightStars(rating) {
        const starLabels = document.querySelectorAll('.star-label');
        starLabels.forEach((label, index) => {
            if (5 - index <= rating) {
                label.style.color = '#fbbf24';
            } else {
                label.style.color = '#d1d5db';
            }
        });
    },

    resetStarHighlight() {
        const selectedRating = document.querySelector('.rating-input input[type="radio"]:checked');
        if (selectedRating) {
            this.updateSelectedRating(parseInt(selectedRating.value));
        } else {
            const starLabels = document.querySelectorAll('.star-label');
            starLabels.forEach(label => {
                label.style.color = '#d1d5db';
            });
        }
    },

    updateSelectedRating(rating) {
        const starLabels = document.querySelectorAll('.star-label');
        starLabels.forEach((label, index) => {
            if (5 - index <= rating) {
                label.style.color = '#fbbf24';
            } else {
                label.style.color = '#d1d5db';
            }
        });
    },

    async submitReview() {
        const reviewForm = document.getElementById('review-form');
        const submitBtn = reviewForm.querySelector('.submit-review');
        const formData = new FormData(reviewForm);

        // Check if user is authenticated
        if (!document.querySelector('meta[name="user-id"]')) {
            this.showNotification('Please login to submit a review', 'warning');
            return;
        }

        // Validate form
        const rating = formData.get('rating');
        const comment = formData.get('comment');

        if (!rating) {
            this.showNotification('Please select a rating', 'warning');
            return;
        }

        if (!comment.trim()) {
            this.showNotification('Please write a review comment', 'warning');
            return;
        }

        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('/reviews', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(formData)
            });

            // Check if response is ok
            if (!response.ok) {
                // Try to get error message from response
                const text = await response.text();
                let errorMessage = 'Server error occurred';
                
                try {
                    const errorData = JSON.parse(text);
                    errorMessage = errorData.message || errorMessage;
                    
                    // Handle validation errors
                    if (errorData.errors) {
                        const firstError = Object.values(errorData.errors)[0];
                        if (Array.isArray(firstError)) {
                            errorMessage = firstError[0];
                        }
                    }
                } catch (e) {
                    // If it's HTML (error page), extract a meaningful message
                    if (text.includes('<!DOCTYPE')) {
                        if (response.status === 401) {
                            errorMessage = 'Please login to submit a review';
                            // Redirect to login if needed
                            setTimeout(() => {
                                window.location.href = '/login';
                            }, 2000);
                        } else if (response.status === 422) {
                            errorMessage = 'Please check your input and try again';
                        } else if (response.status === 500) {
                            errorMessage = 'Server error. Please try again later';
                        } else {
                            errorMessage = `Server error (${response.status})`;
                        }
                    } else {
                        errorMessage = text.substring(0, 100) + '...';
                    }
                }
                throw new Error(errorMessage);
            }

            const data = await response.json();

            if (data.success) {
                this.showNotification('Review submitted successfully! It will be visible after approval.', 'success');
                reviewForm.reset();
                this.resetStarHighlight();
                
                // Optionally add the review to the list immediately (if approved)
                if (data.review && data.review.is_approved) {
                    this.addReviewToList(data.review);
                }
            } else {
                throw new Error(data.message || 'Failed to submit review');
            }
        } catch (error) {
            console.error('Error submitting review:', error);
            let errorMessage = error.message || 'Error submitting review';
            
            if (error.message && (error.message.includes("Unexpected token '<'") || error.message.includes('SyntaxError: Unexpected token'))) {
                errorMessage = 'Session may have expired. Please login again to submit your review.';
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            }
            
            this.showNotification(errorMessage, 'error');
        }

        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    },

    addReviewToList(review) {
        const reviewsList = document.getElementById('reviews-list');
        const noReviews = reviewsList.querySelector('.no-reviews');
        
        if (noReviews) {
            noReviews.remove();
        }

        const reviewElement = this.createReviewElement(review);
        reviewsList.insertAdjacentHTML('afterbegin', reviewElement);
    },

    createReviewElement(review) {
        const stars = Array.from({length: 5}, (_, i) => 
            `<i class="fas fa-star ${i < review.rating ? 'filled' : ''}"></i>`
        ).join('');

        return `
            <div class="review-item" data-review-id="${review.id}">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">
                            ${review.user.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="reviewer-details">
                            <h5 class="reviewer-name">${review.user.name}</h5>
                            <div class="review-rating">
                                ${stars}
                            </div>
                        </div>
                    </div>
                    <div class="review-date">
                        ${new Date(review.created_at).toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'short', 
                            day: 'numeric' 
                        })}
                    </div>
                </div>
                <div class="review-content">
                    <p class="review-text">${review.comment}</p>
                </div>
            </div>
        `;
    },

    // Utility Functions
    updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count, #cart-count');
        cartCountElements.forEach(element => {
            element.textContent = count;
            
            // Add animation
            element.style.transform = 'scale(1.2)';
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 200);
        });
    },

    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.product-notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification
        const notification = document.createElement('div');
        notification.className = `product-notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);

        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.remove();
        });

        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
    },

    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || icons.info;
    }
};

// Add notification styles
const notificationStyles = `
<style>
.product-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.product-notification.show {
    transform: translateX(0);
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    font-weight: 500;
    min-width: 300px;
    max-width: 400px;
}

.notification-success .notification-content {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.notification-error .notification-content {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
}

.notification-warning .notification-content {
    background: linear-gradient(135deg, #d97706, #b45309);
    color: white;
}

.notification-info .notification-content {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.notification-close {
    background: none;
    border: none;
    color: inherit;
    font-size: 1.25rem;
    cursor: pointer;
    margin-left: auto;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.notification-close:hover {
    opacity: 1;
}

.image-zoom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
}

.zoom-modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.zoom-modal-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
}

.zoom-modal-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 8px;
}

.zoom-modal-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.2s ease;
}

.zoom-modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

@media (max-width: 768px) {
    .product-notification {
        top: 10px;
        right: 10px;
        left: 10px;
        transform: translateY(-100%);
    }
    
    .product-notification.show {
        transform: translateY(0);
    }
    
    .notification-content {
        min-width: auto;
    }
}
</style>
`;

// Inject notification styles
document.head.insertAdjacentHTML('beforeend', notificationStyles);

// Export for use
window.ProductDetails = ProductDetails;