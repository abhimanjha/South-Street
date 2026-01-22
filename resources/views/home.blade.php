@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                <div class="d-flex justify-content-start ps-0">
                    @foreach($trendingProducts->take(4) as $product)
                        <div class="product-item me-3">
                            @if($product->is_featured || $product->is_trending)
                                <div class="bestseller-badge">Bestseller</div>
                            @endif
                            <button class="wishlist-btn"><i class="far fa-heart"></i></button>
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
                                <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                                    <h3 class="product-name-minimal">{{ $product->name }}</h3>
                                </a>
                                <div class="product-footer">
                                    <p class="product-price-minimal">
                                        @if($product->discount_price)
                                            <span class="price-discounted">₹{{ number_format($product->discount_price, 0) }}</span>
                                            <span class="price-original" style="text-decoration: line-through; color: #999; font-size: 0.8em;">₹{{ number_format($product->price, 0) }}</span>
                                        @else
                                            ₹{{ number_format($product->price, 0) }}
                                        @endif
                                    </p>
                                    <button class="add-to-bag-btn" data-product-id="{{ $product->id }}" {{ $product->variants->count() > 0 ? 'data-variant-id=""' : '' }} data-quantity="1">
                                        <i class="fas fa-shopping-bag"></i> ADD
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if($trendingProducts->count() > 4)
            <div class="carousel-item">
                <div class="d-flex justify-content-start ps-0">
                    @foreach($trendingProducts->skip(4)->take(4) as $product)
                        <div class="product-item me-3">
                            @if($product->is_featured || $product->is_trending)
                                <div class="bestseller-badge">Bestseller</div>
                            @endif
                            <button class="wishlist-btn"><i class="far fa-heart"></i></button>
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
                                <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                                    <h3 class="product-name-minimal">{{ $product->name }}</h3>
                                </a>
                                <div class="product-footer">
                                    <p class="product-price-minimal">
                                        @if($product->discount_price)
                                            <span class="price-discounted">₹{{ number_format($product->discount_price, 0) }}</span>
                                            <span class="price-original" style="text-decoration: line-through; color: #999; font-size: 0.8em;">₹{{ number_format($product->price, 0) }}</span>
                                        @else
                                            ₹{{ number_format($product->price, 0) }}
                                        @endif
                                    </p>
                                    <button class="add-to-bag-btn" data-product-id="{{ $product->id }}" data-variant-id="" data-quantity="1">
                                        <i class="fas fa-shopping-bag"></i> ADD
                                    </button>
                                </div>
                            </div>
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

<!-- Best Seller Section -->
<section class="best-seller-section">
    <div class="best-seller-container">
        <div class="best-seller-content">
            <h2 class="best-seller-title">Best Seller</h2>
            <p class="best-seller-subtitle">Discover the Finest Fabrics in Our Top Selling Collection.</p>
            <a href="{{ route('products.index', ['sort' => 'best_selling']) }}" class="best-seller-btn">Shop now</a>
        </div>
        <div class="best-seller-image-wrapper">
            <img src="{{ asset('imgs/women1.jpg') }}" alt="Best Seller Collection" class="best-seller-img">
        </div>
    </div>
</section>

<!-- Editorial / Offer Banner -->
<section class="new-arrivals-section">
  <h2 class="section-title">NEW ARRIVALS</h2>

  <div class="banner-wrapper">
    <img
      src="{{ asset('imgs/new_arr.jpg') }}"
      alt="New Arrivals Banner"
      class="banner-image"
    />
  </div>
</section>


<!-- Custom Tailoring Service Row -->
<section class="cta-section">
    <div class="cta-container">
        <div class="cta-content">
            <div class="cta-text">
                <h2 class="cta-title">CUSTOM DESIGN SERVICE</h2>
                <p class="cta-description">Create your unique piece with our expert designers
Step into a world of personalized fashion where every detail is thoughtfully crafted just for you. Our expert designers work closely with you to understand your vision, preferences, and occasion. From selecting premium fabrics to refining the smallest design elements, we ensure each piece is tailored to perfection—resulting in an outfit that reflects your individuality, confidence, and timeless style.</p>
                <a href="{{ route('custom-tailoring.create') }}" class="cta-button">BOOK CONSULTATION</a>
            </div>
            <div class="cta-image">
                <img src="/imgs/cr1.jpg" alt="Custom Design" class="cta-img">
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
                <div class="d-flex justify-content-start ps-0">
                    @foreach($newArrivals->take(4) as $product)
                        <div class="product-item me-3">
                            @if($product->is_featured || $product->is_trending)
                                <div class="bestseller-badge">Bestseller</div>
                            @elseif($product->discount_percentage > 0)
                                <div class="bestseller-badge" style="background: #d32f2f;">-{{ $product->discount_percentage }}%</div>
                            @endif
                            <button class="wishlist-btn"><i class="far fa-heart"></i></button>
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
                                <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                                    <h3 class="product-name-minimal">{{ $product->name }}</h3>
                                </a>
                                <div class="product-footer">
                                    <p class="product-price-minimal">
                                        @if($product->discount_price)
                                            <span class="price-discounted">₹{{ number_format($product->discount_price, 0) }}</span>
                                            <span class="price-original" style="text-decoration: line-through; color: #999; font-size: 0.8em;">₹{{ number_format($product->price, 0) }}</span>
                                        @else
                                            ₹{{ number_format($product->price, 0) }}
                                        @endif
                                    </p>
                                    <button class="add-to-bag-btn" data-product-id="{{ $product->id }}" {{ $product->variants->count() > 0 ? 'data-variant-id=""' : '' }} data-quantity="1">
                                        <i class="fas fa-shopping-bag"></i> ADD
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if($newArrivals->count() > 4)
            <div class="carousel-item">
                <div class="d-flex justify-content-start ps-0">
                    @foreach($newArrivals->skip(4)->take(4) as $product)
                        <div class="product-item me-3">
                            @if($product->is_featured || $product->is_trending)
                                <div class="bestseller-badge">Bestseller</div>
                            @elseif($product->discount_percentage > 0)
                                <div class="bestseller-badge" style="background: #d32f2f;">-{{ $product->discount_percentage }}%</div>
                            @endif
                            <button class="wishlist-btn"><i class="far fa-heart"></i></button>
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
                                <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                                    <h3 class="product-name-minimal">{{ $product->name }}</h3>
                                </a>
                                <div class="product-footer">
                                    <p class="product-price-minimal">
                                        @if($product->discount_price)
                                            <span class="price-discounted">₹{{ number_format($product->discount_price, 0) }}</span>
                                            <span class="price-original" style="text-decoration: line-through; color: #999; font-size: 0.8em;">₹{{ number_format($product->price, 0) }}</span>
                                        @else
                                            ₹{{ number_format($product->price, 0) }}
                                        @endif
                                    </p>
                                    <button class="add-to-bag-btn" data-product-id="{{ $product->id }}" {{ $product->variants->count() > 0 ? 'data-variant-id=""' : '' }} data-quantity="1">
                                        <i class="fas fa-shopping-bag"></i> ADD
                                    </button>
                                </div>
                            </div>
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
<section class="modern-category-section">
    <div class="container-fluid custom-container">
        <div class="modern-section-header">
            <h2 class="modern-title">Shop by Category</h2>
            <p class="modern-subtitle">Curated essentials for your everyday wardrobe — clean fits, premium fabrics.</p>
        </div>

@php
    use Illuminate\Support\Str;
@endphp

<div class="row g-3 g-md-4">
@foreach($categories as $category)
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('products.category', $category->slug) }}"
           class="text-decoration-none shadow-sm rounded-3 overflow-hidden d-block modern-category-card">

            <div class="position-relative overflow-hidden" style="aspect-ratio: 3/4;">

                {{-- Men category custom image --}}
                @if(Str::contains(strtolower($category->name), 'men'))
                    <img src="{{ asset('imgs/men3.jpg') }}"
                         alt="{{ $category->name }}"
                         class="w-100 h-100 object-fit-cover modern-img">

                {{-- Category image from DB --}}
                @elseif($category->image)
                    <img src="{{ Storage::url($category->image) }}"
                         alt="{{ $category->name }}"
                         class="w-100 h-100 object-fit-cover modern-img">

                {{-- Fallback --}}
                @else
                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                        <span class="text-muted fw-bold">{{ strtoupper($category->name) }}</span>
                    </div>
                @endif

                <div class="modern-overlay d-flex flex-column justify-content-end p-3 p-md-4 position-absolute top-0 start-0 w-100 h-100">
                    <h3 class="text-white h5 mb-1 fw-bold">{{ strtoupper($category->name) }}</h3>
                    <div class="modern-cat-cta text-white-50 small text-uppercase fw-semibold">
                        Explore Collection <i class="bi bi-arrow-right"></i>
                    </div>
                </div>

            </div>
        </a>
    </div>
@endforeach
</div>


        <div class="modern-bottom-action">
            <a href="{{ route('products.index') }}" class="modern-btn-outline">View All Products</a>
        </div>
    </div>
</section>

<section class="modern-features-bar">
    <div class="container-fluid custom-container">
        <div class="modern-features-grid">
            <div class="modern-feature-item">
                <div class="modern-icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
                <div class="modern-feature-info">
                    <h4>Free Shipping</h4>
                    <p>On orders above ₹500</p>
                </div>
            </div>
            <div class="modern-feature-item">
                <div class="modern-icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
                <div class="modern-feature-info">
                    <h4>Easy Returns</h4>
                    <p>7-day return policy</p>
                </div>
            </div>
            <div class="modern-feature-item">
                <div class="modern-icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="modern-feature-info">
                    <h4>Quality Assured</h4>
                    <p>Premium materials only</p>
                </div>
            </div>
            <div class="modern-feature-item">
                <div class="modern-icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="modern-feature-info">
                    <h4>24/7 Support</h4>
                    <p>Always here to help</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


