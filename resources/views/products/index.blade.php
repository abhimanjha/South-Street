@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - Shop' : 'Shop All Products')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="container">
        <!-- Page Header -->
        <div class="section-header-minimal">
            <h2 class="section-title-minimal force-underline">
                {{ isset($category) ? strtoupper($category->name) : 'SHOP ALL' }}
            </h2>
        </div>

        <!-- Filters (keep functionality, but use existing button styles) -->
        <div class="d-flex flex-wrap gap-2 mb-4">
            @if(isset($category))
                <a href="{{ route('products.category', $category->slug) }}" class="btn btn-sm {{ !request('filter') ? 'btn-dark' : 'btn-outline-dark' }}">All</a>
                <a href="{{ route('products.category', ['category' => $category->slug, 'filter' => 'new']) }}" class="btn btn-sm {{ request('filter') === 'new' ? 'btn-dark' : 'btn-outline-dark' }}">New</a>
                <a href="{{ route('products.category', ['category' => $category->slug, 'filter' => 'featured']) }}" class="btn btn-sm {{ request('filter') === 'featured' ? 'btn-dark' : 'btn-outline-dark' }}">Featured</a>
                <a href="{{ route('products.category', ['category' => $category->slug, 'filter' => 'discount']) }}" class="btn btn-sm {{ request('filter') === 'discount' ? 'btn-dark' : 'btn-outline-dark' }}">Discount</a>
                <a href="{{ route('products.category', ['category' => $category->slug, 'filter' => 'trending']) }}" class="btn btn-sm {{ request('filter') === 'trending' ? 'btn-dark' : 'btn-outline-dark' }}">Trending</a>
            @else
                <a href="{{ route('products.index') }}" class="btn btn-sm {{ !request('filter') ? 'btn-dark' : 'btn-outline-dark' }}">All</a>
                <a href="{{ route('products.index', ['filter' => 'new']) }}" class="btn btn-sm {{ request('filter') === 'new' ? 'btn-dark' : 'btn-outline-dark' }}">New</a>
                <a href="{{ route('products.index', ['filter' => 'featured']) }}" class="btn btn-sm {{ request('filter') === 'featured' ? 'btn-dark' : 'btn-outline-dark' }}">Featured</a>
                <a href="{{ route('products.index', ['filter' => 'discount']) }}" class="btn btn-sm {{ request('filter') === 'discount' ? 'btn-dark' : 'btn-outline-dark' }}">Discount</a>
                <a href="{{ route('products.index', ['filter' => 'trending']) }}" class="btn btn-sm {{ request('filter') === 'trending' ? 'btn-dark' : 'btn-outline-dark' }}">Trending</a>
            @endif
        </div>

        <!-- Products Grid (Home-page card UI) -->
        @if($products->count() > 0)
            <div class="row g-3">
                @foreach($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-item">
                            @if($product->is_featured || (isset($product->is_trending) && $product->is_trending))
                                <div class="bestseller-badge">Bestseller</div>
                            @elseif($product->discount_price)
                                <div class="bestseller-badge" style="background: #d32f2f;">-{{ $product->discount_percentage }}%</div>
                            @endif
                            <button class="wishlist-btn"><i class="far fa-heart"></i></button>

                            <a href="{{ route('products.show', $product->slug) }}" class="product-link-minimal">
                                <div class="product-image-container">
                                @php
                                    $imagePath = $product->images->first() ? $product->images->first()->image_path : 'imgs/men1.jpg';
                                    $imageUrl = str_starts_with($imagePath, 'imgs/') ? asset($imagePath) : asset('storage/' . $imagePath);
                                @endphp
                                <img src="{{ $imageUrl }}"
                                     class="product-img-main"
                                     alt="{{ $product->name }}"
                                     onload="console.log('Image loaded successfully:', this.src);"
                                     onerror="console.log('Image failed to load:', this.src); this.src='{{ asset('imgs/men1.jpg') }}';">
                                @if($product->images->count() > 1)
                                @php
                                    $hoverImagePath = $product->images->skip(1)->first()->image_path;
                                    $hoverImageUrl = str_starts_with($hoverImagePath, 'imgs/') ? asset($hoverImagePath) : asset('storage/' . $hoverImagePath);
                                @endphp
                                <img src="{{ $hoverImageUrl }}" 
                                     class="product-img-hover"
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


