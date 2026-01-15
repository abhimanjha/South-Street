@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="product-details-page">
    <div class="container-fluid px-4 py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <div class="main-image-wrapper">
                        <img id="main-product-image" 
                             src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : 'data:image/svg+xml;base64,' . base64_encode('<svg width="400" height="500" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f3f4f6"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="16" fill="#9ca3af" text-anchor="middle" dy=".3em">No Image Available</text></svg>') }}" 
                             alt="{{ $product->name }}" 
                             class="main-product-image">
                        <div class="image-zoom-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                    
                    @if($product->images->count() > 1)
                    <div class="thumbnail-gallery">
                        @foreach($product->images as $index => $image)
                        <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" 
                             data-image="{{ asset('storage/' . $image->image_path) }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="thumbnail-image">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Product Header -->
                    <div class="product-header">
                        @if($product->brand)
                        <p class="product-brand">{{ $product->brand }}</p>
                        @endif
                        <h1 class="product-title">{{ $product->name }}</h1>
                        
                        <!-- Rating -->
                        @if($product->total_reviews > 0)
                        <div class="product-rating">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $product->average_rating ? 'filled' : '' }}"></i>
                                @endfor
                                <span class="rating-value">{{ number_format($product->average_rating, 1) }}</span>
                            </div>
                            <span class="rating-count">({{ $product->total_reviews }} {{ Str::plural('review', $product->total_reviews) }})</span>
                        </div>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="product-pricing">
                        @if($product->discount_price)
                            <div class="price-row">
                                <span class="current-price">₹{{ number_format($product->discount_price, 2) }}</span>
                                <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                                <span class="discount-badge">{{ $product->discount_percentage }}% OFF</span>
                            </div>
                        @else
                            <div class="price-row">
                                <span class="current-price">₹{{ number_format($product->price, 2) }}</span>
                            </div>
                        @endif
                        
                        @if($product->is_featured)
                        <div class="product-badges">
                            <span class="badge featured-badge">Featured</span>
                        </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="product-description">
                        <p>{{ $product->description }}</p>
                        @if($product->full_description)
                        <div class="full-description">
                            <p>{{ $product->full_description }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Variants -->
                    @if($product->variants->count() > 0)
                    <div class="product-variants">
                        @php
                            $sizes = $product->variants->pluck('size')->unique()->filter();
                            $colors = $product->variants->pluck('color')->unique()->filter();
                        @endphp

                        @if($sizes->count() > 0)
                        <div class="variant-group">
                            <label class="variant-label">Size:</label>
                            <div class="size-options">
                                @foreach($sizes as $size)
                                <button class="size-option" data-size="{{ $size }}">{{ $size }}</button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($colors->count() > 0)
                        <div class="variant-group">
                            <label class="variant-label">Color:</label>
                            <div class="color-options">
                                @foreach($colors as $color)
                                <button class="color-option" 
                                        style="background-color: {{ $color }}" 
                                        data-color="{{ $color }}" 
                                        title="{{ $color }}"></button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Quantity & Actions -->
                    <div class="product-actions">
                        <div class="quantity-selector">
                            <label class="quantity-label">Quantity:</label>
                            <div class="quantity-controls">
                                <button class="qty-btn decrease" id="decrease-qty">-</button>
                                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" readonly>
                                <button class="qty-btn increase" id="increase-qty">+</button>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button class="btn btn-primary add-to-cart" id="add-to-cart-btn">
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>
                            <button class="btn btn-secondary add-to-wishlist" id="add-to-wishlist-btn">
                                <i class="fas fa-heart"></i>
                                Wishlist
                            </button>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="stock-status">
                        @if($product->isInStock())
                            <span class="in-stock">
                                <i class="fas fa-check-circle"></i>
                                In Stock ({{ $product->stock_quantity }} available)
                            </span>
                        @else
                            <span class="out-of-stock">
                                <i class="fas fa-times-circle"></i>
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <!-- Product Meta -->
                    @if($product->fabric || $product->material || $product->care_instructions)
                    <div class="product-meta">
                        @if($product->fabric)
                        <div class="meta-item">
                            <strong>Fabric:</strong> {{ $product->fabric }}
                        </div>
                        @endif
                        @if($product->material)
                        <div class="meta-item">
                            <strong>Material:</strong> {{ $product->material }}
                        </div>
                        @endif
                        @if($product->care_instructions)
                        <div class="meta-item">
                            <strong>Care Instructions:</strong> {{ $product->care_instructions }}
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="reviews-section">
                    <div class="reviews-header">
                        <h3 class="reviews-title">Customer Reviews</h3>
                        @if($product->total_reviews > 0)
                        <div class="reviews-summary">
                            <div class="overall-rating">
                                <span class="rating-number">{{ number_format($product->average_rating, 1) }}</span>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $product->average_rating ? 'filled' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-text">Based on {{ $product->total_reviews }} {{ Str::plural('review', $product->total_reviews) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="reviews-content">
                        <div id="reviews-list" class="reviews-list">
                            @forelse($product->reviews->where('is_approved', true) as $review)
                            <div class="review-item" data-review-id="{{ $review->id }}">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div class="reviewer-details">
                                            <h5 class="reviewer-name">{{ $review->user->name }}</h5>
                                            <div class="review-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-date">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="review-content">
                                    <p class="review-text">{{ $review->comment }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="no-reviews">
                                <div class="no-reviews-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4>No reviews yet</h4>
                                <p>Be the first to review this product!</p>
                            </div>
                            @endforelse
                        </div>

                        @auth
                        <div class="add-review-section">
                            <h4 class="add-review-title">Write a Review</h4>
                            <form id="review-form" class="review-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="form-group">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input">
                                        @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                                        <label for="star{{ $i }}" class="star-label">
                                            <i class="fas fa-star"></i>
                                        </label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="comment" class="form-label">Your Review</label>
                                    <textarea id="comment" name="comment" class="form-control" rows="4" 
                                              placeholder="Share your experience with this product..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary submit-review">
                                    <i class="fas fa-paper-plane"></i>
                                    Submit Review
                                </button>
                                <button type="button" class="btn btn-secondary test-connection" style="margin-left: 10px;">
                                    <i class="fas fa-wifi"></i>
                                    Test Connection
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="login-prompt">
                            <p>Please <a href="{{ route('login') }}">login</a> to write a review.</p>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Load script and initialize
    (function() {
        let retryCount = 0;
        const maxRetries = 10;
        
        function initializeProductDetails() {
        if (typeof ProductDetails !== 'undefined') {
                try {
                    // Check if elements exist
                    const quantityInput = document.getElementById('quantity');
                    const addToCartBtn = document.getElementById('add-to-cart-btn');
                    
                    if (!quantityInput || !addToCartBtn) {
                        console.warn('Product details elements not found, retrying...');
                        if (retryCount < maxRetries) {
                            retryCount++;
                            setTimeout(initializeProductDetails, 100);
                        }
                        return;
                    }
                    
            ProductDetails.init({
                productId: {{ $product->id }},
                        maxQuantity: {{ max($product->stock_quantity ?? 10, 1) }},
                        csrfToken: '{{ csrf_token() }}',
                        variants: @json($product->variants ?? []),
                        hasSizes: {{ ($product->variants ?? collect())->whereNotNull('size')->count() > 0 ? 'true' : 'false' }},
                        hasColors: {{ ($product->variants ?? collect())->whereNotNull('color')->count() > 0 ? 'true' : 'false' }}
                    });
                    console.log('ProductDetails initialized successfully');
                } catch (error) {
                    console.error('Error initializing ProductDetails:', error);
                }
            } else {
                // Retry after a short delay if script not loaded yet
                if (retryCount < maxRetries) {
                    retryCount++;
                    setTimeout(initializeProductDetails, 100);
        } else {
                    console.error('ProductDetails script failed to load after multiple retries');
                }
            }
        }

        // Load the script first
        const script = document.createElement('script');
        script.src = '{{ asset('js/product-details.js') }}';
        script.onload = function() {
            // Script loaded, now initialize
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeProductDetails);
            } else {
                // DOM is already ready
                setTimeout(initializeProductDetails, 10);
            }
        };
        script.onerror = function() {
            console.error('Failed to load product-details.js');
        };
        document.head.appendChild(script);
    })();
</script>
@endpush
@endsection
