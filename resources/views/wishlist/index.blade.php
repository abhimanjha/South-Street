@extends('layouts.app')

@section('title', 'My Wishlist - ' . config('app.name'))

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">❤️ My Wishlist</h1>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($wishlists->count() > 0)
        <div class="row">
            @foreach($wishlists as $wishlist)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm wishlist-item" data-product-id="{{ $wishlist->product_id }}">
                    <div class="position-relative">
                        <img src="{{ $wishlist->product->primaryImage ? asset($wishlist->product->primaryImage->image_path) : asset('imgs/placeholder.jpg') }}"
                             class="card-img-top"
                             alt="{{ $wishlist->product->name }}"
                             style="height: 200px; object-fit: cover;">
                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-from-wishlist"
                                data-product-id="{{ $wishlist->product_id }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-2">
                            <a href="{{ route('products.show', $wishlist->product) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($wishlist->product->name, 50) }}
                            </a>
                        </h6>

                        <div class="mb-2">
                            @if($wishlist->product->discount_price)
                                <span class="text-decoration-line-through text-muted small">₹{{ number_format($wishlist->product->price, 2) }}</span>
                                <span class="fw-bold text-success">₹{{ number_format($wishlist->product->discount_price, 2) }}</span>
                                <span class="badge bg-danger ms-1">{{ $wishlist->product->discount_percentage }}% OFF</span>
                            @else
                                <span class="fw-bold">₹{{ number_format($wishlist->product->price, 2) }}</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            @if($wishlist->product->isInStock())
                                <span class="badge bg-success mb-2">In Stock</span>
                            @else
                                <span class="badge bg-danger mb-2">Out of Stock</span>
                            @endif

                            <div class="d-grid gap-2">
                                <a href="{{ route('products.show', $wishlist->product) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                                @if($wishlist->product->isInStock())
                                <button class="btn btn-primary btn-sm add-to-cart-from-wishlist"
                                        data-product-id="{{ $wishlist->product_id }}">
                                    <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-heart text-muted" style="font-size: 4rem;"></i>
            </div>
            <h3 class="text-muted mb-3">Your wishlist is empty</h3>
            <p class="text-muted mb-4">Add items you love to your wishlist. Review them anytime and easily move them to the cart.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
            </a>
        </div>
    @endif
</div>

<style>
.wishlist-item {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.wishlist-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.remove-from-wishlist {
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.remove-from-wishlist:hover {
    opacity: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove from wishlist
    document.querySelectorAll('.remove-from-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const card = this.closest('.wishlist-item');

            if (confirm('Remove this item from your wishlist?')) {
                fetch('{{ route("wishlist.remove") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        card.remove();
                        updateWishlistCount(data.count);

                        // Show empty state if no items left
                        if (document.querySelectorAll('.wishlist-item').length === 0) {
                            location.reload();
                        }
                    } else {
                        alert('Error removing item from wishlist');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error removing item from wishlist');
                });
            }
        });
    });

    // Add to cart from wishlist
    document.querySelectorAll('.add-to-cart-from-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const btn = this;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    updateCartCount(data.cart_count);
                } else {
                    alert('Error adding to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding to cart');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-cart-plus me-1"></i>Add to Cart';
            });
        });
    });
});

function updateWishlistCount(count) {
    // Update wishlist count in navbar if exists
    const wishlistBadge = document.getElementById('wishlist-count');
    if (wishlistBadge) {
        wishlistBadge.textContent = count;
        if (count == 0) {
            wishlistBadge.style.display = 'none';
        } else {
            wishlistBadge.style.display = 'inline';
        }
    }
}

function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}
</script>
@endsection
