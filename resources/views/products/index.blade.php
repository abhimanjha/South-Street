@extends('layouts.app')

@section('title', 'Shop All Products')

<link rel="stylesheet" href="{{ asset('css/products.css') }}">

@section('content')
<div class="products-page modern-shop-override">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">{{ isset($category) ? ucfirst($category->name) : 'Shop All' }}</h1>
            <p class="page-subtitle">{{ isset($category) ? 'Explore our premium ' . strtolower($category->name) . ' collection crafted with excellence' : 'Discover our complete collection of fashion and custom designs, curated for the modern lifestyle' }}</p>
        </div>

        <!-- Modern Filters -->
        <div class="filters-container">
            <a href="{{ route('products.index') }}" class="filter-btn {{ !request('filter') ? 'active' : '' }}">All Products</a>
            <a href="{{ route('products.index', ['filter' => 'new']) }}" class="filter-btn {{ request('filter') === 'new' ? 'active' : '' }}">New Arrivals</a>
            <a href="{{ route('products.index', ['filter' => 'featured']) }}" class="filter-btn {{ request('filter') === 'featured' ? 'active' : '' }}">Best Sellers</a>
            <a href="{{ route('products.index', ['filter' => 'discount']) }}" class="filter-btn {{ request('filter') === 'discount' ? 'active' : '' }}">Offers & Discounts</a>
            <a href="{{ route('products.index', ['filter' => 'trending']) }}" class="filter-btn {{ request('filter') === 'trending' ? 'active' : '' }}">Trending Now</a>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', $product->slug) }}" class="product-link">
                            <div class="product-image-container">
                                @php
                                    $imagePath = $product->images->first() ? $product->images->first()->image_path : 'imgs/men1.jpg';
                                    $imageUrl = str_starts_with($imagePath, 'imgs/') ? asset($imagePath) : asset('storage/' . $imagePath);
                                @endphp
                                <img src="{{ $imageUrl }}" 
                                     class="product-image-main" 
                                     alt="{{ $product->name }}"
                                     onload="console.log('Image loaded successfully:', this.src);"
                                     onerror="console.log('Image failed to load:', this.src); this.src='{{ asset('imgs/men1.jpg') }}';">
                                @if($product->images->count() > 1)
                                @php
                                    $hoverImagePath = $product->images->skip(1)->first()->image_path;
                                    $hoverImageUrl = str_starts_with($hoverImagePath, 'imgs/') ? asset($hoverImagePath) : asset('storage/' . $hoverImagePath);
                                @endphp
                                <img src="{{ $hoverImageUrl }}" 
                                     class="product-image-hover" 
                                     alt="{{ $product->name }}"
                                     onload="console.log('Hover image loaded successfully:', this.src);"
                                     onerror="console.log('Hover image failed to load:', this.src); this.style.display='none';">
                                @endif
                                
                                <!-- Badges -->
                                <div class="product-badges">
                                    <div>
                                        @if($product->is_featured)
                                        <span class="product-badge badge-featured">Featured</span>
                                        @endif
                                        @if($product->created_at->diffInDays() < 30)
                                        <span class="product-badge badge-new">New</span>
                                        @endif
                                    </div>
                                    @if($product->discount_price)
                                    <span class="product-badge badge-discount">{{ $product->discount_percentage }}% OFF</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="product-details">
                                <div class="product-category">{{ $product->category->name ?? 'Fashion' }}</div>
                                <h3 class="product-name">{{ $product->name }}</h3>
                                
                                <div class="product-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="rating-text">(4.5) 128 reviews</span>
                                </div>
                                
                                <div class="product-price">
                                    @if($product->discount_price)
                                        <span class="price-current price-discount">₹{{ number_format($product->discount_price, 2) }}</span>
                                        <span class="price-original">₹{{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="price-current">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                <button class="add-to-cart-btn">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 class="empty-title">No products found</h3>
                <p class="empty-text">Check back later for new arrivals and exciting collections!</p>
            </div>
        @endif
    </div>
</div>
@endsection
