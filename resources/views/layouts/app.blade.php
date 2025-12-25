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
    <link href="{{ asset('css/responsive.css') }}?v=2" rel="stylesheet">
    <link href="{{ asset('css/spa-navigation.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-tailoring.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('js/spa-navigation.js') }}" defer></script>
    @auth
    <script>
        // Simple Notification System
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Notification system initialized');
            
            // Fetch and show notifications
            function fetchNotifications() {
                console.log('Fetching notifications from API...');
                
                fetch('/api/notifications/unread')
                    .then(response => {
                        console.log('API Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Notifications received:', data);
                        
                        if (data.notifications && data.notifications.length > 0) {
                            console.log('Showing', data.notifications.length, 'notifications');
                            data.notifications.forEach((notification, index) => {
                                setTimeout(() => showNotification(notification), index * 2000);
                            });
                        } else {
                            console.log('No notifications to show');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching notifications:', error);
                    });
            }
            
            // Show notification
            function showNotification(notification) {
                console.log('Showing notification:', notification);
                
                const container = document.getElementById('notification-container') || createContainer();
                
                const notif = document.createElement('div');
                notif.className = 'notification-toast';
                notif.innerHTML = `
                    <div class="notification-content">
                        <div class="notification-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="notification-body">
                            <h6>${notification.title}</h6>
                            <p>${notification.message}</p>
                            ${notification.discount_code ? `
                                <div class="notification-code">
                                    Code: ${notification.discount_code} - ${notification.discount_percentage}% OFF
                                </div>
                            ` : ''}
                        </div>
                        <button onclick="this.closest('.notification-toast').remove()" class="notification-close">×</button>
                    </div>
                `;
                
                container.appendChild(notif);
                
                // Slide in
                setTimeout(() => notif.classList.add('show'), 100);
                
                // Auto remove after 10 seconds
                setTimeout(() => {
                    notif.classList.remove('show');
                    setTimeout(() => {
                        notif.remove();
                        markAsRead(notification.id);
                    }, 500);
                }, 10000);
            }
            
            function createContainer() {
                const container = document.createElement('div');
                container.id = 'notification-container';
                document.body.appendChild(container);
                return container;
            }
            
            function markAsRead(notificationId) {
                fetch(`/api/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                }).catch(error => console.error('Error marking as read:', error));
            }
            
            // Fetch immediately
            fetchNotifications();
            
            // Then every 30 seconds
            setInterval(fetchNotifications, 30000);
        });
    </script>
    
    <style>
        #notification-container {
            position: fixed;
            top: 80px;
            right: 20px;
            width: 350px;
            z-index: 9999;
        }
        
        .notification-toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-bottom: 12px;
            border-left: 4px solid #ff9f00;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.5s ease;
        }
        
        .notification-toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .notification-content {
            display: flex;
            padding: 16px;
            gap: 12px;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ff9f00;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }
        
        .notification-body {
            flex: 1;
        }
        
        .notification-body h6 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 600;
            color: #212121;
        }
        
        .notification-body p {
            margin: 0;
            font-size: 13px;
            color: #878787;
        }
        
        .notification-code {
            margin-top: 8px;
            padding: 6px 10px;
            background: #fff4e5;
            border-radius: 4px;
            display: inline-block;
            font-size: 12px;
            color: #ff9f00;
            font-weight: 600;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: #878787;
            cursor: pointer;
            font-size: 24px;
            line-height: 1;
            padding: 0;
            width: 24px;
            height: 24px;
        }
        
        .notification-close:hover {
            color: #212121;
        }
        
        @media (max-width: 768px) {
            #notification-container {
                width: calc(100% - 40px);
                right: 20px;
                left: 20px;
            }
        }
    </style>
    @endauth

    @stack('styles')
</head>
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
      <a href="{{ route('cart.index') }}" class="position-relative text-dark text-decoration-none" data-cart-count-url="{{ route('cart.count') }}">
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
            <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-link" title="Threads">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 192 192">
                <path d="M141.537 88.988a66.667 66.667 0 0 0-2.518-1.143c-1.482-27.307-16.403-42.94-41.457-43.1h-.34c-14.986 0-27.449 6.396-35.12 18.036l13.779 9.452c5.73-8.695 14.925-10.816 21.339-10.816h.229c8.249.053 14.474 2.452 18.503 7.129 2.932 3.405 4.893 8.111 5.864 14.05-7.314-1.243-15.224-1.626-23.68-1.141-23.82 1.371-39.134 15.264-38.105 34.568.522 9.792 5.4 18.216 13.735 23.719 7.047 4.652 16.124 6.927 25.557 6.412 12.458-.683 22.231-5.436 29.05-14.127 5.177-6.585 8.622-15.119 10.28-25.446 1.638.838 3.235 1.756 4.758 2.754 9.015 5.912 14.105 14.39 14.105 23.463 0 17.47-14.432 30.79-34.01 31.402-14.987.47-28.827-4.577-38.938-14.207-10.09-9.614-15.64-23.696-15.64-39.645 0-15.948 5.55-30.03 15.64-39.644 10.111-9.63 23.951-14.677 38.938-14.207 19.578.612 34.01 13.932 34.01 31.402v.125h16.875v-.125c0-26.656-21.557-46.88-50.885-47.652-18.64-.49-35.716 5.887-48.044 17.952C9.93 44.102 3.125 61.355 3.125 80.5s6.805 36.398 19.133 48.463c12.328 12.065 29.404 18.442 48.044 17.952 29.328-.772 50.885-20.996 50.885-47.652 0-13.205-7.118-25.115-19.65-32.275z"/>
              </svg>
            </a>
            <a href="#" class="social-link" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
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
            <li><a href="#">Store Locator</a></li>
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
// Make these functions global for SPA navigation
window.updateCartCount = function() {
  fetch('{{ route("cart.count") }}')
    .then(res => res.json())
    .then(data => {
      const badge = document.getElementById('cart-count');
      if (badge) {
        badge.textContent = data.count;
      }
    })
    .catch(err => console.error('Error updating cart count:', err));
};

@auth
window.updateWishlistCount = function() {
  fetch('{{ route("wishlist.count") }}')
    .then(res => res.json())
    .then(data => {
      const wishlistBadge = document.getElementById('wishlist-count');
      if (wishlistBadge) {
        if (data.count > 0) {
          wishlistBadge.textContent = data.count;
          wishlistBadge.style.display = 'inline';
        } else {
          wishlistBadge.style.display = 'none';
        }
      }
    })
    .catch(err => console.error('Error updating wishlist count:', err));
};
@endauth

// Initial load
document.addEventListener('DOMContentLoaded', function() {
  updateCartCount();
  @auth
  updateWishlistCount();
  @endauth
});

// Update on SPA navigation
window.addEventListener('spa-navigated', function() {
  updateCartCount();
  @auth
  updateWishlistCount();
  @endauth
});
</script>


    @stack('scripts')
</body>
</html>
