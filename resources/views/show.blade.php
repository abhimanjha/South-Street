@extends('layouts.app')

@section('title', $product->name . ' - Garmeva')
@section('meta_description', $product->description)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-blue-600 hover:underline">{{ $product->category->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $product->name }}</span>
    </nav>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
            <div class="mb-4 relative">
                @if($product->images->count() > 0)
                    <img id="main-product-image" src="{{ Storage::url($product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow-lg cursor-pointer">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-400">No Image Available</span>
                    </div>
                @endif
                <!-- Zoom Overlay Hint -->
                <div class="image-zoom-overlay hidden absolute inset-0 bg-black bg-opacity-25 flex items-center justify-center text-white text-2xl rounded-lg cursor-pointer">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $image)
                        <img src="{{ Storage::url($image->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="thumbnail-item w-full h-24 object-cover rounded cursor-pointer border-2 hover:border-blue-600 transition"
                             data-image="{{ Storage::url($image->image_path) }}">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            <p class="text-gray-600 mb-4">SKU: {{ $product->sku }}</p>

            <!-- Rating -->
            <div class="flex items-center mb-4">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                </div>
                <span class="ml-2 text-gray-600">{{ number_format($product->average_rating, 1) }} ({{ $product->total_reviews }} reviews)</span>
            </div>

            <!-- Price -->
            <div class="mb-6">
                @if($product->discount_price)
                    <div class="flex items-center gap-4">
                        <span class="text-4xl font-bold text-gray-900">₹{{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-2xl text-gray-500 line-through">₹{{ number_format($product->price, 2) }}</span>
                        <span class="bg-red-500 text-white px-3 py-1 rounded">-{{ $product->discount_percentage }}%</span>
                    </div>
                @else
                    <span class="text-4xl font-bold text-gray-900">₹{{ number_format($product->price, 2) }}</span>
                @endif
                <p class="text-sm text-gray-600 mt-2">Inclusive of all taxes</p>
            </div>

            <!-- Variants -->
            @if($product->variants->count() > 0)
                <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selected-variant" value="">

                    <!-- Size Selection -->
                    @php
                        $sizes = $product->variants->pluck('size')->unique()->filter();
                    @endphp
                    @if($sizes->count() > 0)
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">Select Size</label>
                            <div class="flex gap-2">
                                @foreach($sizes as $size)
                                    <button type="button" 
                                            class="size-option px-4 py-2 border rounded hover:border-blue-600 hover:bg-blue-50 transition"
                                            data-size="{{ $size }}">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Color Selection -->
                    @php
                        $colors = $product->variants->pluck('color')->unique()->filter();
                    @endphp
                    @if($colors->count() > 0)
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">Select Color</label>
                            <div class="flex gap-2">
                                @foreach($colors as $color)
                                    @php
                                        $colorCode = $product->variants->where('color', $color)->first()->color_code;
                                    @endphp
                                    <button type="button"
                                            class="color-option w-10 h-10 rounded-full border-2 hover:border-blue-600 transition"
                                            style="background-color: {{ $colorCode }}"
                                            data-color="{{ $color }}"
                                            title="{{ $color }}">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity -->
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Quantity</label>
                        <div class="flex items-center gap-3">
                            <button type="button" id="decrease-qty" class="px-4 py-2 border rounded hover:bg-gray-100 transition">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-20 text-center border rounded py-2">
                            <button type="button" id="increase-qty" class="px-4 py-2 border rounded hover:bg-gray-100 transition">+</button>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div id="stock-status" class="mb-4">
                        @if($product->stock_quantity > 0)
                            <span class="text-green-600 font-semibold">In Stock</span>
                        @else
                            <span class="text-red-600 font-semibold">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 mb-6">
                        <button type="button" id="add-to-cart-btn" class="flex-1 bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-shopping-bag me-2"></i> Add to Cart
                        </button>
                        <button type="button" onclick="buyNow()" class="flex-1 bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                            Buy Now
                        </button>
                    </div>
                </form>
            @else
                <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Quantity</label>
                        <div class="flex items-center gap-3">
                            <button type="button" id="decrease-qty" class="px-4 py-2 border rounded hover:bg-gray-100 transition">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-20 text-center border rounded py-2">
                            <button type="button" id="increase-qty" class="px-4 py-2 border rounded hover:bg-gray-100 transition">+</button>
                        </div>
                    </div>
                     <!-- Action Buttons -->
                    <div class="flex gap-4 mb-6">
                        <button type="button" id="add-to-cart-btn" class="flex-1 bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-shopping-bag me-2"></i> Add to Cart
                        </button>
                         <button type="button" onclick="buyNow()" class="flex-1 bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                            Buy Now
                        </button>
                    </div>
                </form>
            @endif

            <!-- Wishlist Button -->
            @auth
                <button id="add-to-wishlist-btn" class="flex items-center justify-center w-full border py-3 rounded-lg hover:bg-gray-50 transition {{ $inWishlist ? 'in-wishlist' : '' }}">
                    <i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart me-2"></i>
                    {{ $inWishlist ? 'In Wishlist' : 'Add to Wishlist' }}
                </button>
            @endauth

            <!-- Product Info -->
            <div class="mt-8 space-y-4">
                @if($product->brand)
                    <div>
                        <span class="font-semibold">Brand:</span>
                        <span class="text-gray-700">{{ $product->brand }}</span>
                    </div>
                @endif
                @if($product->fabric)
                    <div>
                        <span class="font-semibold">Fabric:</span>
                        <span class="text-gray-700">{{ $product->fabric }}</span>
                    </div>
                @endif
                @if($product->material)
                    <div>
                        <span class="font-semibold">Material:</span>
                        <span class="text-gray-700">{{ $product->material }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Description -->
    <div class="mt-12">
        <div class="border-b">
            <div class="flex gap-8">
                <button class="tab-btn pb-4 font-semibold border-b-2 border-blue-600 text-blue-600" data-tab="description">Description</button>
                <button class="tab-btn pb-4 font-semibold" data-tab="reviews">Reviews ({{ $product->total_reviews }})</button>
            </div>
        </div>

        <!-- Description Tab -->
        <div id="description-tab" class="tab-content py-8">
            <div class="prose max-w-none">
                {!! nl2br(e($product->full_description ?? $product->description)) !!}
            </div>
            @if($product->care_instructions)
                <div class="mt-6">
                    <h3 class="font-semibold text-lg mb-2">Care Instructions</h3>
                    <p class="text-gray-700">{{ $product->care_instructions }}</p>
                </div>
            @endif
        </div>

        <!-- Reviews Tab -->
        <div id="reviews-tab" class="tab-content py-8 hidden">
            @foreach($product->reviews as $review)
                <div class="border-b pb-6 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="font-semibold">{{ substr($review->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="font-semibold">{{ $review->user->name }}</div>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    @if($review->title)
                        <h4 class="font-semibold mb-2">{{ $review->title }}</h4>
                    @endif
                    <p class="text-gray-700">{{ $review->comment }}</p>
                    @if($review->is_verified_purchase)
                        <span class="inline-block mt-2 text-sm text-green-600">✓ Verified Purchase</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6">You May Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                        <a href="{{ route('products.show', $relatedProduct) }}">
                            @if($relatedProduct->images->first())
                                <img src="{{ Storage::url($relatedProduct->images->first()->image_path) }}" alt="{{ $relatedProduct->name }}" class="w-full h-64 object-cover rounded-t-lg">
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-t-lg"></div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $relatedProduct->name }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-bold text-gray-900">₹{{ number_format($relatedProduct->final_price, 2) }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="{{ asset('js/product-details.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof ProductDetails !== 'undefined') {
            ProductDetails.init({
                productId: {{ $product->id }},
                csrfToken: '{{ csrf_token() }}',
                maxQuantity: {{ $product->stock_quantity ?? 10 }},
                variants: @json($product->variants),
                hasSizes: {{ $product->variants->whereNotNull('size')->count() > 0 ? 'true' : 'false' }},
                hasColors: {{ $product->variants->whereNotNull('color')->count() > 0 ? 'true' : 'false' }} 
            });
        }
    });

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-blue-600', 'text-blue-600');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            this.classList.add('border-blue-600', 'text-blue-600');
            document.getElementById(this.dataset.tab + '-tab').classList.remove('hidden');
        });
    });

    // Buy Now - Submit Form
    window.buyNow = function() {
        const form = document.getElementById('add-to-cart-form');
         // Check variant selection if needed
         const variantInput = document.getElementById('selected-variant');
        if (variantInput && !variantInput.value && {{ $product->variants->count() > 0 ? 'true' : 'false' }}) {
             alert('Please select a variant option.');
             return;
        }
        form.submit();
    };
</script>
@endpush
@endsection