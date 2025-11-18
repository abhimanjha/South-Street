@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="product-image-gallery">
                <div class="main-image-container mb-3">
                    <img id="main-product-image" src="{{ $product->primaryImage ? asset($product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}" alt="{{ $product->name }}" class="img-fluid main-product-image">
                </div>
                @if($product->images->count() > 1)
                <div class="thumbnail-images d-flex gap-2 overflow-auto">
                    @foreach($product->images as $image)
                    <img src="{{ asset($image->image_path) }}" alt="{{ $product->name }}" class="thumbnail-image" data-image="{{ asset($image->image_path) }}" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent;">
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="product-details">
                <h1 class="product-title mb-3">{{ $product->name }}</h1>

                @if($product->brand)
                <p class="product-brand text-muted mb-2">Brand: {{ $product->brand }}</p>
                @endif

                <div class="product-price mb-3">
                    @if($product->discount_price)
                        <span class="price-discounted fs-4 fw-bold">${{ number_format($product->discount_price, 2) }}</span>
                        <span class="price-original text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</span>
                        <span class="badge bg-danger ms-2">{{ $product->discount_percentage }}% OFF</span>
                    @else
                        <span class="price-regular fs-4 fw-bold">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->is_featured)
                <div class="badge bg-primary mb-3">Featured Product</div>
                @endif

                <div class="product-description mb-4">
                    <h5>Description</h5>
                    <p>{{ $product->description }}</p>
                    @if($product->full_description)
                    <p class="mt-2">{{ $product->full_description }}</p>
                    @endif
                </div>

                <!-- Variants (Size/Color) -->
                @if($product->variants->count() > 0)
                <div class="product-variants mb-4">
                    @php
                        $sizes = $product->variants->pluck('size')->unique()->filter();
                        $colors = $product->variants->pluck('color')->unique()->filter();
                    @endphp

                    @if($sizes->count() > 0)
                    <div class="mb-3">
                        <h6>Size:</h6>
                        <div class="size-options">
                            @foreach($sizes as $size)
                            <button class="btn btn-outline-secondary size-option" data-size="{{ $size }}">{{ $size }}</button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($colors->count() > 0)
                    <div class="mb-3">
                        <h6>Color:</h6>
                        <div class="color-options">
                            @foreach($colors as $color)
                            <button class="btn color-option" style="background-color: {{ $color }}; width: 30px; height: 30px; border-radius: 50%; border: 2px solid #ddd;" data-color="{{ $color }}" title="{{ $color }}"></button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Quantity -->
                <div class="quantity-selector mb-4">
                    <h6>Quantity:</h6>
                    <div class="input-group" style="width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" id="decrease-qty">-</button>
                        <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}">
                        <button class="btn btn-outline-secondary" type="button" id="increase-qty">+</button>
                    </div>
                </div>

                <!-- Add to Cart -->
                <div class="product-actions mb-4">
                    <button class="btn btn-primary btn-lg me-2" id="add-to-cart-btn">
                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                    <button class="btn btn-outline-secondary btn-lg" id="add-to-wishlist-btn">
                        <i class="fas fa-heart me-2"></i>Add to Wishlist
                    </button>
                </div>

                <!-- Stock Status -->
                <div class="stock-status mb-4">
                    @if($product->isInStock())
                        <span class="text-success"><i class="fas fa-check-circle me-1"></i>In Stock ({{ $product->stock_quantity }} available)</span>
                    @else
                        <span class="text-danger"><i class="fas fa-times-circle me-1"></i>Out of Stock</span>
                    @endif
                </div>

                <!-- Product Meta -->
                <div class="product-meta">
                    @if($product->fabric)
                    <p><strong>Fabric:</strong> {{ $product->fabric }}</p>
                    @endif
                    @if($product->material)
                    <p><strong>Material:</strong> {{ $product->material }}</p>
                    @endif
                    @if($product->care_instructions)
                    <p><strong>Care Instructions:</strong> {{ $product->care_instructions }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="reviews-section">
                <h3 class="mb-4">Customer Reviews</h3>

                @if($product->reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($product->reviews as $review)
                        <div class="review-item border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <div class="rating mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-2">{{ $review->comment }}</p>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                @endif

                @auth
                <div class="add-review mt-4">
                    <h5>Write a Review</h5>
                    <form id="review-form">
                        @csrf
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-select" id="rating" name="rating" required>
                                <option value="">Select rating</option>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
/* Product Image Gallery */
.main-product-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.thumbnail-image {
    border-radius: 4px;
    transition: all 0.3s ease;
}

.thumbnail-image:hover,
.thumbnail-image.active {
    border-color: #007bff !important;
    transform: scale(1.05);
}

/* Product Details */
.product-title {
    font-size: 2rem;
    font-weight: 300;
    color: var(--primary-black);
}

.product-price {
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 1rem;
}

.price-discounted {
    color: #d32f2f;
}

.price-original {
    font-size: 1rem;
}

.product-description h5 {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

/* Variants */
.size-option,
.color-option {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

.size-option.active {
    background-color: var(--primary-black);
    color: white;
}

.color-option.active {
    border: 2px solid var(--primary-black) !important;
}

/* Quantity Selector */
.quantity-selector input {
    border-left: none;
    border-right: none;
}

/* Product Actions */
.product-actions .btn {
    padding: 0.75rem 1.5rem;
}

/* Reviews */
.reviews-list {
    max-height: 400px;
    overflow-y: auto;
}

.review-item {
    margin-bottom: 1rem;
}

.rating {
    color: #ffc107;
}

/* Responsive */
@media (max-width: 768px) {
    .main-product-image {
        height: 300px;
    }

    .product-title {
        font-size: 1.5rem;
    }

    .thumbnail-images {
        justify-content: center;
    }
}
</style>

<script>
// Image Gallery
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('main-product-image');
    const thumbnails = document.querySelectorAll('.thumbnail-image');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            // Add active class to clicked thumbnail
            this.classList.add('active');
            // Update main image
            mainImage.src = this.dataset.image;
        });
    });

    // Set first thumbnail as active
    if (thumbnails.length > 0) {
        thumbnails[0].classList.add('active');
    }
});

// Quantity Selector
document.getElementById('decrease-qty').addEventListener('click', function() {
    const qtyInput = document.getElementById('quantity');
    const currentValue = parseInt(qtyInput.value);
    if (currentValue > 1) {
        qtyInput.value = currentValue - 1;
    }
});

document.getElementById('increase-qty').addEventListener('click', function() {
    const qtyInput = document.getElementById('quantity');
    const currentValue = parseInt(qtyInput.value);
    const maxValue = parseInt(qtyInput.max);
    if (currentValue < maxValue) {
        qtyInput.value = currentValue + 1;
    }
});

// Variant Selection
document.querySelectorAll('.size-option').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.size-option').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
    });
});

document.querySelectorAll('.color-option').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.color-option').forEach(btn => {
            btn.classList.remove('active');
            btn.style.border = '2px solid #ddd';
        });
        this.classList.add('active');
        this.style.border = '2px solid var(--primary-black)';
    });
});

// Add to Cart
document.getElementById('add-to-cart-btn').addEventListener('click', function() {
    const quantity = document.getElementById('quantity').value;
    const selectedSize = document.querySelector('.size-option.active')?.dataset.size;
    const selectedColor = document.querySelector('.color-option.active')?.dataset.color;

    // Find variant ID based on selected size and color
    let variantId = null;
    @if($product->variants->count() > 0)
    @foreach($product->variants as $variant)
    if (('{{ $variant->size }}' === selectedSize || !selectedSize) && ('{{ $variant->color }}' === selectedColor || !selectedColor)) {
        variantId = {{ $variant->id }};
    }
    @endforeach
    @endif

    // Send AJAX request to add to cart
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
            quantity: quantity,
            variant_id: variantId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Update cart count in navbar if exists
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.cart_count;
            }
        } else {
            alert('Error adding to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding to cart');
    });
});

// Add to Wishlist
document.getElementById('add-to-wishlist-btn').addEventListener('click', function() {
    @auth
    fetch('{{ route("wishlist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: {{ $product->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            updateWishlistCount();
        } else {
            alert(data.message || 'Error adding to wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding to wishlist');
    });
    @else
    alert('Please login to add items to wishlist');
    @endauth
});

// Review Form
document.getElementById('review-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    // Here you would typically send an AJAX request to submit the review
    alert('Review submitted successfully!');
    this.reset();
});
</script>
@endsection
