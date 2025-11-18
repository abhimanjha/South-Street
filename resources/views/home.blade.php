@extends('layouts.app')

@section('title', 'Home - FashionHub')

@section('content')
<!-- Hero Section - Full Width Image Banner -->
<section class="hero-banner">
    <div class="hero-slide">
        <img src="{{ asset('imgs/men3.jpg') }}" alt="Men's Collection" class="grid-image">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1 class="hero-title">NEW COLLECTION</h1>
                <p class="hero-subtitle">Discover Your Style</p>
                <a href="{{ route('products.index') }}" class="hero-cta">SHOP NOW</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories Grid -->
<section class="featured-grid">
    <div class="grid-container">
        <div class="grid-item grid-large">
            <a href="{{ route('products.category', 'women') }}" class="grid-link">
                <img src="{{ asset('imgs/women1.jpg') }}" alt="Women's Collection" class="grid-image">
                <div class="grid-overlay">
                    <h2 class="grid-title">WOMEN</h2>
                </div>
            </a>
        </div>
        <div class="grid-item">
            <a href="{{ route('products.category', 'men') }}" class="grid-link">
                <img src="{{ asset('imgs/men1.jpg') }}" alt="Men's Collection" class="grid-image">
                <div class="grid-overlay">
                    <h2 class="grid-title">MEN</h2>
                </div>
            </a>
        </div>
        <div class="grid-item">
            <a href="{{ route('products.category', 'kids') }}" class="grid-link">
                <img src="{{ asset('imgs/kid1.jpg') }}" alt="Kid's Collection" class="grid-image">
                <div class="grid-overlay">
                    <h2 class="grid-title">KIDS</h2>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Products - Minimal Grid -->
<section class="products-section">
    <div class="section-header-minimal">
        <h2 class="section-title-minimal">NEW IN</h2>
    </div>
    <div class="products-grid">
        @foreach($featuredProducts as $product)
            <div class="product-item">
                <a href="{{ route('products.show', $product) }}" class="product-link-minimal">
                    <div class="product-image-container">
                        @if($product->images->first())
                            <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="product-img-main">
                            @if($product->images->count() > 1)
                                <img src="{{ Storage::url($product->images->skip(1)->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-img-hover">
                            @endif
                        @else
                            <div class="product-placeholder">
                                <span>NO IMAGE</span>
                            </div>
                        @endif
                        @if($product->discount_percentage > 0)
                            <span class="product-badge">-{{ $product->discount_percentage }}%</span>
                        @endif
                    </div>
                    <div class="product-details">
                        <h3 class="product-name-minimal">{{ $product->name }}</h3>
                        <p class="product-price-minimal">
                            @if($product->discount_price)
                                <span class="price-discounted">₹{{ number_format($product->discount_price, 0) }}</span>
                                <span class="price-original">₹{{ number_format($product->price, 0) }}</span>
                            @else
                                ₹{{ number_format($product->price, 0) }}
                            @endif
                        </p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

<!-- Editorial Banner -->
<section class="editorial-banner">
    <div class="editorial-content">
        <img src="{{ asset('imgs/men2.jpg') }}" alt="Editorial" class="editorial-image">
        <div class="editorial-text">
            <h2 class="editorial-title">STYLE ESSENTIALS</h2>
            <p class="editorial-subtitle">Timeless pieces for every wardrobe</p>
            <a href="{{ route('products.index') }}" class="editorial-link">DISCOVER</a>
        </div>
    </div>
</section>

<!-- Trending Products -->
@if($trendingProducts->count() > 0)
<section class="products-section">
    <div class="section-header-minimal">
        <h2 class="section-title-minimal">TRENDING</h2>
    </div>
    <div class="products-grid">
        @foreach($trendingProducts as $product)
            <div class="product-item">
                <a href="{{ route('products.show', $product) }}" class="product-link-minimal">
                    <div class="product-image-container">
                        @if($product->images->first())
                            <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-img-main">
                            @if($product->images->count() > 1)
                                <img src="{{ Storage::url($product->images->skip(1)->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-img-hover">
                            @endif
                        @else
                            <div class="product-placeholder">
                                <span>NO IMAGE</span>
                            </div>
                        @endif
                    </div>
                    <div class="product-details">
                        <h3 class="product-name-minimal">{{ $product->name }}</h3>
                        <p class="product-category-minimal">{{ $product->category->name }}</p>
                        <p class="product-price-minimal">₹{{ number_format($product->final_price, 0) }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- Custom Design CTA -->
<section class="cta-section">
    <div class="cta-container">
        <div class="cta-content">
            <div class="cta-text">
                <h2 class="cta-title">CUSTOM DESIGN SERVICE</h2>
                <p class="cta-description">Create your unique piece with our expert designers</p>
                <a href="{{ route('tailoring.create') }}" class="cta-button">BOOK CONSULTATION</a>
            </div>
            <div class="cta-image">
                <img src="/images/custom-design.jpg" alt="Custom Design" class="cta-img">
            </div>
        </div>
    </div>
</section>

<!-- Shop by Category -->
<section class="category-section">
    <div class="section-header-minimal">
        <h2 class="section-title-minimal">SHOP BY CATEGORY</h2>
    </div>
    <div class="category-grid">
        @foreach($categories as $category)
            <div class="category-item">
                <a href="{{ route('products.category', $category->slug) }}" class="category-link-minimal">
                    <div class="category-image-box">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="category-img">
                        @else
                            <div class="category-placeholder">
                                <span>{{ strtoupper($category->name) }}</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="category-name-minimal">{{ $category->name }}</h3>
                </a>
            </div>
        @endforeach
    </div>
</section>

<!-- New Arrivals -->
<section class="products-section">
    <div class="section-header-minimal">
        <h2 class="section-title-minimal">NEW ARRIVALS</h2>
        <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="view-all-minimal">VIEW ALL</a>
    </div>
    <div class="products-grid">
        @foreach($newArrivals as $product)
            <div class="product-item">
                <a href="{{ route('products.show', $product) }}" class="product-link-minimal">
                    <div class="product-image-container">
                        @if($product->images->first())
                            <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-img-main">
                            @if($product->images->count() > 1)
                                <img src="{{ Storage::url($product->images->skip(1)->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-img-hover">
                            @endif
                        @else
                            <div class="product-placeholder">
                                <span>NO IMAGE</span>
                            </div>
                        @endif
                        @if($product->discount_percentage > 0)
                            <span class="product-badge sale">SALE</span>
                        @endif
                    </div>
                    <div class="product-details">
                        <h3 class="product-name-minimal">{{ $product->name }}</h3>
                        <p class="product-price-minimal">
                            @if($product->discount_price)
                                <span class="price-discounted">₹{{ number_format($product->discount_price, 0) }}</span>
                                <span class="price-original">₹{{ number_format($product->price, 0) }}</span>
                            @else
                                ₹{{ number_format($product->price, 0) }}
                            @endif
                        </p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

<!-- Features Bar -->
<section class="features-bar">
    <div class="features-container">
        <div class="feature-item">
            <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
            <div class="feature-text">
                <h4>FREE SHIPPING</h4>
                <p>On orders above ₹500</p>
            </div>
        </div>
        <div class="feature-item">
            <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <div class="feature-text">
                <h4>EASY RETURNS</h4>
                <p>7-day return policy</p>
            </div>
        </div>
        <div class="feature-item">
            <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="feature-text">
                <h4>QUALITY ASSURED</h4>
                <p>Premium materials only</p>
            </div>
        </div>
        <div class="feature-item">
            <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <div class="feature-text">
                <h4>24/7 SUPPORT</h4>
                <p>Always here to help</p>
            </div>
        </div>
    </div>
</section>
@endsection