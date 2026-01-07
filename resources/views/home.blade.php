@extends('layouts.app')

@section('title', 'Home - SouthStreet')

@section('content')
<!-- Hero Banner Carousel -->
<section class="hero-banner">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('imgs/cr1.jpg') }}" alt="Men's Collection" class="d-block w-100 carousel-image">
                <div class="carousel-caption">
                    <h1 class="hero-title">NEW COLLECTION</h1>
                    <p class="hero-subtitle">Discover Your Style</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products.index') }}" class="hero-cta btn-primary">SHOP NOW</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('imgs/cr2.jpg') }}" alt="Women's Collection" class="d-block w-100 carousel-image">
                <div class="carousel-caption">
                    <h1 class="hero-title">WOMEN'S FASHION</h1>
                    <p class="hero-subtitle">Elegant and Stylish</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products.category', 'women') }}" class="hero-cta btn-primary">SHOP NOW</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('imgs/kid1.jpg') }}" alt="Kid's Collection" class="d-block w-100 carousel-image">
                <div class="carousel-caption">
                    <h1 class="hero-title">KIDS' COLLECTION</h1>
                    <p class="hero-subtitle">Fun and Comfortable</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products.category', 'kids') }}" class="hero-cta btn-primary">SHOP NOW</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>


<!-- Trending Products (Horizontal Carousel) -->
@if($trendingProducts->count() > 0)
<section class="products-section pt-5">
    <div class="section-header-minimal">
        <h2 class="section-title-minimal force-underline">TRENDING</h2>
    </div>
    <div id="trendingCarousel" class="carousel slide" data-bs-ride="false">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="d-flex justify-content-start">
                    @foreach($trendingProducts->take(4) as $product)
                        <div class="product-item me-3">
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
            </div>
            @if($trendingProducts->count() > 4)
            <div class="carousel-item">
                <div class="d-flex justify-content-start">
                    @foreach($trendingProducts->skip(4)->take(4) as $product)
                        <div class="product-item me-3">
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
            </div>
            @endif
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#trendingCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#trendingCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
@endif

<!-- Editorial / Offer Banner -->
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

<!-- Custom Tailoring Service Row -->
<section class="cta-section">
    <div class="cta-container">
        <div class="cta-content">
            <div class="cta-text">
                <h2 class="cta-title">CUSTOM DESIGN SERVICE</h2>
                <p class="cta-description">Create your unique piece with our expert designers</p>
                <a href="{{ route('custom-tailoring.create') }}" class="cta-button">BOOK CONSULTATION</a>
            </div>
            <div class="cta-image">
                <img src="/images/custom-design.jpg" alt="Custom Design" class="cta-img">
            </div>
        </div>
    </div>
</section>

<!-- New In / New Arrivals (Horizontal Carousel) -->
<section class="products-section">
    <div class="section-header-minimal">
        <h2 class="section-title-minimal force-underline">NEW IN</h2>
        <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="view-all-minimal">VIEW ALL</a>
    </div>
    <div id="newInCarousel" class="carousel slide" data-bs-ride="false">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="d-flex justify-content-start">
                    @foreach($newArrivals->take(4) as $product)
                        <div class="product-item me-3">
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
            </div>
            @if($newArrivals->count() > 4)
            <div class="carousel-item">
                <div class="d-flex justify-content-start">
                    @foreach($newArrivals->skip(4)->take(4) as $product)
                        <div class="product-item me-3">
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
            </div>
            @endif
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#newInCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#newInCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- Shop by Category -->
<section class="category-section">
    <div class="section-header-minimal">
        <h2 class="section-title-minimal force-underline">SHOP BY CATEGORY</h2>
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

<!-- Feature Bar -->
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