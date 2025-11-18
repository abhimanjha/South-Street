<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Garmeva')</title>
    <meta name="description" content="@yield('meta_description', 'Premium fashion and custom design services')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<style>
    /* South Street Navbar Styles */
.southstreet-header {
  background-color: #fff;
  font-family: 'Montserrat', sans-serif;
  border-bottom: 1px solid #eee;
}

.logo-name {
  font-size: 1.6rem;
  letter-spacing: 1px;
}

.text-primary {
  color: #d4a017 !important; /* gold accent */
}

.search-bar {
  max-width: 500px;
}

.search-input {
  border: 1px solid #ddd;
  border-radius: 50px 0 0 50px;
  padding: 0.6rem 1rem;
  font-size: 0.95rem;
}

.search-btn {
  border-radius: 0 50px 50px 0;
  background-color: #000;
  color: #fff;
  padding: 0.6rem 1rem;
  border: none;
}

.search-btn:hover {
  background-color: #333;
}

.cart-badge {
  font-size: 0.7rem;
}

.southstreet-nav .nav-link {
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: color 0.3s ease;
}

.southstreet-nav .nav-link:hover {
  color: #d4a017;
}
/* ============ Footer (Matching Navbar) ============ */
    footer.southstreet-footer {
      background-color: #fff;
      border-top: 1px solid #eee;
      color: #333;
      font-family: 'Montserrat', sans-serif;
    }

    footer .footer-main {
      padding: 50px 5%;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 40px;
    }

    footer h3 {
      color: #000;
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 15px;
    }

    footer ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    footer li {
      margin-bottom: 8px;
    }

    footer a {
      color: #555;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s ease;
    }

    footer a:hover {
      color: #d4a017;
    }

    .footer-bottom {
      border-top: 1px solid #eee;
      padding: 20px 5%;
      background-color: #fafafa;
      text-align: center;
      font-size: 14px;
    }

    .footer-logo {
      font-weight: 700;
      font-size: 20px;
      color: #000;
    }

    .footer-logo span.text-primary {
      color: #d4a017;
    }

    .footer-links a {
      margin: 0 10px;
      color: #555;
      text-decoration: none;
    }

    .footer-links a:hover {
      color: #d4a017;
    }

    .footer-copy {
      color: #777;
      font-size: 13px;
      margin-top: 10px;
    }

    .back-to-top {
      text-align: center;
      padding: 10px;
      background: #f8f8f8;
      border-top: 1px solid #eee;
    }

    .back-to-top a {
      color: #000;
      font-weight: 500;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .back-to-top a:hover {
      color: #d4a017;
    }

    .footer-logo-section {
      text-align: left;
    }

    .footer-logo {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .tagline {
      color: #777;
      font-size: 14px;
      margin-bottom: 15px;
      font-style: italic;
    }

    .social-icons {
      display: flex;
      gap: 15px;
    }

    .social-link {
      color: #555;
      font-size: 18px;
      transition: color 0.3s ease;
    }

    .social-link:hover {
      color: #d4a017;
    }

    @media (max-width: 768px) {
      footer .footer-main {
        grid-template-columns: 1fr 1fr;
      }
      .footer-links {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
      }
      .footer-logo-section {
        grid-column: span 2;
        text-align: center;
      }
      .social-icons {
        justify-content: center;
      }
    }
</style>
<body class="bg-light">
    <div class="min-vh-100">
<!-- South Street Navbar -->
<header class="southstreet-header shadow-sm">
  <div class="container-fluid px-4 py-2 d-flex align-items-center justify-content-between">
    
    <!-- Left: Brand Logo -->
    <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center text-decoration-none">
      <span class="logo-name fw-bold text-dark">South<span class="text-primary">Street</span></span>
    </a>

    <!-- Center: Search Bar -->
    <form action="{{ route('products.search') }}" method="GET" class="d-flex align-items-center search-bar mx-3 flex-grow-1">
      <input type="text" name="q" class="form-control search-input" placeholder="Search branded fashion or custom designs...">
      <button type="submit" class="btn search-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
          <path d="M11 6a5 5 0 1 1-1.001 9.9A5 5 0 0 1 11 6zM6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11z"/>
        </svg>
      </button>
    </form>

    <!-- Right: Account, Wishlist, Cart -->
    <div class="d-flex align-items-center gap-4">
      <!-- Account -->
      <div class="nav-item">
        @auth
          <a href="{{ route('account.dashboard') }}" class="text-dark text-decoration-none small">
            Hello, <strong>{{ auth()->user()->name }}</strong>
          </a>
        @else
          <a href="{{ url('/login') }}" class="text-dark text-decoration-none small">
            Sign In / Register
          </a>
        @endauth
      </div>

      <!-- Wishlist -->
      <a href="{{ route('wishlist.index') }}" class="position-relative text-dark text-decoration-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
          <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748z"/>
        </svg>
        @auth
        <span id="wishlist-count" class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
        @endauth
      </a>

      <!-- Cart -->
      <a href="{{ route('cart.index') }}" class="position-relative text-dark text-decoration-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
          <path d="M8 1a2 2 0 0 0-2 2v1H3.5A1.5 1.5 0 0 0 2 5.5v8A1.5 1.5 0 0 0 3.5 15h9A1.5 1.5 0 0 0 14 13.5v-8A1.5 1.5 0 0 0 12.5 4H10V3a2 2 0 0 0-2-2zM5 3a3 3 0 1 1 6 0v1H5V3z"/>
        </svg>
        <span id="cart-count" class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
      </a>
    </div>
  </div>

  <!-- Bottom Nav -->
  <nav class="southstreet-nav py-2 border-top border-bottom bg-white">
    <div class="container-fluid d-flex justify-content-center gap-4 flex-wrap">
      <a href="{{ route('products.index') }}" class="nav-link text-dark">Shop All</a>
      <a href="{{ route('tailoring.create') }}" class="nav-link text-dark">Custom Tailoring</a>
      <a href="{{ route('products.category', 'men') }}" class="nav-link text-dark">Men</a>
      <a href="{{ route('products.category', 'women') }}" class="nav-link text-dark">Women</a>
      <a href="{{ route('products.category', 'kids') }}" class="nav-link text-dark">Kids</a>
      <a href="{{ route('contact') }}" class="nav-link text-dark">Support</a>
    </div>
  </nav>
</header>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>


    <!-- Footer -->
    <footer class="southstreet-footer mt-5">
      <div class="back-to-top" id="back-to-top">
        <a href="#top">Back to top ↑</a>
      </div>

      <div class="footer-main container" style="padding: 40px !important;">
        <!-- Logo and Tagline Section -->
        <div class="footer-logo-section">
          <div class="footer-logo">
            <span class="logo-name fw-bold text-dark">South<span class="text-primary">Street</span></span>
          </div>
          <p class="tagline">Style That Speaks</p>
          <div class="social-icons">
            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-link"><i class="fab fa-threads"></i></a>
            <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
          </div>
        </div>

        <!-- Shop Section -->
        <div>
          <h3>Shop</h3>
          <ul>
            <li><a href="{{ route('products.category', 'men') }}">Men's Wear</a></li>
            <li><a href="{{ route('products.category', 'women') }}">Women's Wear</a></li>
            <li><a href="{{ route('products.category', 'kids') }}">Kids' Collection</a></li>
            <li><a href="{{ route('tailoring.create') }}">Custom Tailoring</a></li>
            <li><a href="#">New Arrivals</a></li>
            <li><a href="#">Sale & Offers</a></li>
          </ul>
        </div>

        <!-- Custom Services Section -->
        <div>
          <h3>Custom Services</h3>
          <ul>
            <li><a href="#">Book Appointment</a></li>
            <li><a href="#">Measurement Guide</a></li>
            <li><a href="#">Tailoring Process</a></li>
            <li><a href="{{ route('contact') }}">Contact Us</a></li>
          </ul>
        </div>

        <!-- Customer Support Section -->
        <div>
          <h3>Customer Support</h3>
          <ul>
            <li><a href="#">Track Order</a></li>
            <li><a href="{{ route('faq') }}">FAQs</a></li>
            <li><a href="#">Size Guide</a></li>
            <li><a href="{{ route('return-policy') }}">Returns & Exchange</a></li>
            <li><a href="#">Shipping Info</a></li>
            <li><a href="{{ route('contact') }}">Contact Us</a></li>
          </ul>
        </div>

        <!-- Quick Links Section -->
        <div>
          <h3>Quick Links</h3>
          <ul>
            <li><a href="{{ route('about') }}">About Us</a></li>
            <li><a href="#">Our Story</a></li>
            <li><a href="#">Store Locator</a></li>
            <li><a href="#">Testimonials</a></li>
          </ul>
        </div>

        <!-- Partner With Us Section -->
        <div>
          <h3>Partner With Us</h3>
          <ul>
            <li><a href="#">Become a Seller</a></li>
            <li><a href="#">Tailoring Partnership</a></li>
            <li><a href="#">Wholesale Inquiry</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <div class="footer-links">
            <a href="{{ route('terms') }}">Terms & Conditions</a> |
            <a href="{{ route('privacy') }}">Privacy Policy</a> |
            <a href="{{ route('refund-policy') }}">Refund Policy</a> |
            <a href="{{ route('shipping-policy') }}">Shipping Policy</a>
          </div>
          <div class="footer-copy">© 2025 SouthStreet — All Rights Reserved</div>
          <div class="payment-icons">
            <i class="fab fa-cc-visa"></i>
            <i class="fab fa-cc-mastercard"></i>
            <i class="fab fa-cc-paypal"></i>
            <i class="fab fa-cc-amex"></i>
          </div>
        </div>
      </div>
    </footer>




 <script>
function updateCartCount() {
  fetch('{{ route("cart.count") }}')
    .then(res => res.json())
    .then(data => {
      document.getElementById('cart-count').textContent = data.count;
    });
}
updateCartCount();

@auth
function updateWishlistCount() {
  fetch('{{ route("wishlist.count") }}')
    .then(res => res.json())
    .then(data => {
      const wishlistBadge = document.getElementById('wishlist-count');
      if (data.count > 0) {
        wishlistBadge.textContent = data.count;
        wishlistBadge.style.display = 'inline';
      } else {
        wishlistBadge.style.display = 'none';
      }
    });
}
updateWishlistCount();
@endauth
</script>


    @stack('scripts')
</body>
</html>
