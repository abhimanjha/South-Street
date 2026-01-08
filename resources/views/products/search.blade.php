@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '"')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Search Results for "{{ $query }}"</h1>

            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="product-item">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-link-minimal">
                                    <div class="product-image-container">
                                        <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('imgs/placeholder.jpg') }}" class="product-img-main" alt="{{ $product->name }}">
                                        @if($product->images->count() > 1)
                                        <img src="{{ asset('storage/' . $product->images->skip(1)->first()->image_path) }}" class="product-img-hover" alt="{{ $product->name }}">
                                        @endif
                                        @if($product->is_featured)
                                        <div class="product-badge">Featured</div>
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

                <div class="d-flex justify-content-center">
                    {{ $products->appends(['q' => $query])->links() }}
                </div>
            @else
                <div class="text-center">
                    <h3>No products found</h3>
                    <p>Try searching with different keywords.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Browse All Products</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
