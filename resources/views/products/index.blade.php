@extends('layouts.app')

@section('title', 'Shop All Products')

@section('content')
<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">{{ isset($category) ? ucfirst($category->name) : 'Shop All' }}</h1>
            <p class="text-muted">{{ isset($category) ? 'Explore our ' . strtolower($category->name) . ' collection' : 'Discover our complete collection of fashion and custom designs' }}</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('products.index') }}" class="btn {{ !request('filter') ? 'btn-primary' : 'btn-outline-primary' }}">All Products</a>
                <a href="{{ route('products.index', ['filter' => 'new']) }}" class="btn {{ request('filter') === 'new' ? 'btn-primary' : 'btn-outline-primary' }}">New Arrivals</a>
                <a href="{{ route('products.index', ['filter' => 'featured']) }}" class="btn {{ request('filter') === 'featured' ? 'btn-primary' : 'btn-outline-primary' }}">Best Sellers</a>
                <a href="{{ route('products.index', ['filter' => 'discount']) }}" class="btn {{ request('filter') === 'discount' ? 'btn-primary' : 'btn-outline-primary' }}">Offers & Discounts</a>
                <a href="{{ route('products.index', ['filter' => 'trending']) }}" class="btn {{ request('filter') === 'trending' ? 'btn-primary' : 'btn-outline-primary' }}">Trending Now</a>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-item">
                        <a href="{{ route('products.show', $product->slug) }}" class="product-link-minimal">
                            <div class="product-image-container">
                                <img src="{{ $product->images->first() ? asset($product->images->first()->image_path) : asset('imgs/placeholder.jpg') }}" class="product-img-main" alt="{{ $product->name }}">
                                @if($product->images->count() > 1)
                                <img src="{{ asset($product->images->skip(1)->first()->image_path) }}" class="product-img-hover" alt="{{ $product->name }}">
                                @endif
                                @if($product->is_featured)
                                <div class="product-badge">Featured</div>
                                @endif
                                @if($product->discount_price)
                                <div class="product-badge discount-badge">{{ $product->discount_percentage }}% OFF</div>
                                @endif
                            </div>
                            <div class="product-details">
                                <h5 class="product-name-minimal">{{ $product->name }}</h5>
                                <p class="product-category-minimal">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                <div class="product-price-minimal">
                                    @if($product->discount_price)
                                        <span class="price-discounted">${{ number_format($product->discount_price, 2) }}</span>
                                        <span class="price-original">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        ${{ number_format($product->price, 2) }}
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center">
            <h3>No products found</h3>
            <p>Check back later for new arrivals!</p>
        </div>
    @endif
</div>

<style>
.product-item {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: #fff;
}

.product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-link-minimal {
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-image-container {
    position: relative;
    overflow: hidden;
    aspect-ratio: 3/4;
}

.product-img-main {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.3s ease;
}

.product-img-hover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-item:hover .product-img-hover {
    opacity: 1;
}

.product-item:hover .product-img-main {
    opacity: 0;
}

.product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #d4a017;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.discount-badge {
    top: 10px;
    right: 10px;
    left: auto;
    background: #e74c3c;
}

.product-details {
    padding: 15px;
}

.product-name-minimal {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-category-minimal {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 10px;
}

.product-price-minimal {
    font-size: 1.1rem;
    font-weight: 600;
    color: #d4a017;
}

.price-discounted {
    color: #e74c3c;
}

.price-original {
    text-decoration: line-through;
    color: #999;
    font-size: 0.9rem;
    margin-left: 8px;
}
</style>
@endsection
