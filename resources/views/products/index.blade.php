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
                        @if($product->is_featured || (isset($product->is_trending) && $product->is_trending))
                            <div class="bestseller-badge">Bestseller</div>
                        @elseif($product->discount_price)
                            <div class="bestseller-badge" style="background: #d32f2f;">-{{ $product->discount_percentage }}%</div>
                        @endif
                        <button class="wishlist-btn"><i class="far fa-heart"></i></button>

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
                                
                                @if($product->variants->count() > 0)
                                    <div class="color-count-badge">
                                         <span class="color-swatch-mini"></span>
                                         <span>{{ $product->variants->unique('color')->count() }}</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                            
                        <div class="product-details">
                            <div class="product-code">#{{ $product->sku ?? 'U'.str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</div>
                            <a href="{{ route('products.show', $product->slug) }}" style="text-decoration: none; color: inherit;">
                                <h3 class="product-name">{{ $product->name }}</h3>
                            </a>
                            
                            <div class="product-footer">
                                <div class="product-price">
                                    @if($product->discount_price)
                                        <span class="price-current price-discount">₹{{ number_format($product->discount_price, 2) }}</span>
                                        <span class="price-original" style="text-decoration: line-through; color: #999; font-size: 0.8em;">₹{{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="price-current">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                <button class="add-to-bag-btn" data-product-id="{{ $product->id }}" data-variant-id="" data-quantity="1">
                                    <i class="fas fa-shopping-bag me-2"></i>ADD
                                </button>
                            </div>
                        </div>
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
