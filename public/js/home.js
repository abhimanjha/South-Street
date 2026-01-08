// SouthStreet Home Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap Carousel with proper settings
    initBootstrapCarousel();

    // Product Carousels
    initProductCarousels();
    
    // Add to Cart for product cards
    initCardCartActions();

    // Mobile Navigation
    initMobileNav();

    // Smooth Scrolling
    initSmoothScrolling();

    // Lazy Loading
    initLazyLoading();
});

// Bootstrap Carousel Initialization
function initBootstrapCarousel() {
    const heroCarousel = document.getElementById('heroCarousel');
    if (heroCarousel) {
        // Initialize Bootstrap carousel with custom options
        const carousel = new bootstrap.Carousel(heroCarousel, {
            interval: 5000,
            wrap: true,
            pause: 'hover',
            ride: 'carousel'
        });

        // Add touch/swipe support for mobile
        let startX = 0;
        let endX = 0;

        heroCarousel.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });

        heroCarousel.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        });

        function handleSwipe() {
            const threshold = 50;
            const diff = startX - endX;

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    // Swipe left - next slide
                    carousel.next();
                } else {
                    // Swipe right - previous slide
                    carousel.prev();
                }
            }
        }
    }
}
        indicator.addEventListener('click', () => {
            showSlide(index);
            stopAutoSlide();
            startAutoSlide();
        });
    });

    // Start auto-sliding
    startAutoSlide();

    // Pause on hover
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        heroSection.addEventListener('mouseenter', stopAutoSlide);
        heroSection.addEventListener('mouseleave', startAutoSlide);
    }
}

// Product Carousel Functionality
function initProductCarousels() {
    const carousels = document.querySelectorAll('.product-carousel');

    carousels.forEach(carousel => {
        const container = carousel.querySelector('.carousel-container');
        const prevBtn = carousel.querySelector('.carousel-nav.prev');
        const nextBtn = carousel.querySelector('.carousel-nav.next');

        if (!container || !prevBtn || !nextBtn) return;

        const scrollAmount = 320; // Product card width + gap

        nextBtn.addEventListener('click', () => {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });

        prevBtn.addEventListener('click', () => {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        // Touch/swipe support for mobile
        let isDown = false;
        let startX;
        let scrollLeft;

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
            container.style.cursor = 'grabbing';
        });

        container.addEventListener('mouseleave', () => {
            isDown = false;
            container.style.cursor = 'grab';
        });

        container.addEventListener('mouseup', () => {
            isDown = false;
            container.style.cursor = 'grab';
        });

        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 2;
            container.scrollLeft = scrollLeft - walk;
        });

        // Touch events for mobile
        container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });

        container.addEventListener('touchmove', (e) => {
            if (!startX) return;
            const x = e.touches[0].pageX - container.offsetLeft;
            const walk = (x - startX) * 2;
            container.scrollLeft = scrollLeft - walk;
        });
    });
}

// Add to Cart actions for product cards
function initCardCartActions() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const buttons = document.querySelectorAll('.add-to-bag-btn');
    if (!buttons.length) return;

    buttons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            const productId = btn.getAttribute('data-product-id');
            const variantId = btn.getAttribute('data-variant-id') || null;
            const quantity = parseInt(btn.getAttribute('data-quantity') || '1', 10);

            if (!productId || !csrf) {
                console.error('Missing productId or CSRF token');
                return;
            }

            btn.disabled = true;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ADDING';

            try {
                const res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity,
                        variant_id: variantId || null
                    })
                });

                const data = await res.json();
                if (data && data.success) {
                    updateCartCountUI(data.cart_count);
                    btn.innerHTML = '<i class="fas fa-check"></i> ADDED';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 1200);
                } else {
                    throw new Error(data?.message || 'Failed to add to cart');
                }
            } catch (err) {
                console.error(err);
                btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ERROR';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 1200);
            }
        });
    });
}

function updateCartCountUI(count) {
    const desktop = document.getElementById('cart-count');
    const mobile = document.getElementById('cart-count-mobile');
    [desktop, mobile].forEach(el => {
        if (el) {
            el.textContent = count;
            el.style.transform = 'scale(1.2)';
            setTimeout(() => { el.style.transform = 'scale(1)'; }, 200);
        }
    });
}

// Mobile Navigation
function initMobileNav() {
    const navItems = document.querySelectorAll('.mobile-nav .nav-item');

    // Update active state based on current page
    const currentPath = window.location.pathname;
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPath.includes(href.split('/').pop())) {
            item.classList.add('active');
        }
    });

    // Add click animations
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(212, 160, 23, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = '50%';
            ripple.style.top = '50%';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.marginLeft = '-10px';
            ripple.style.marginTop = '-10px';

            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });
}

// Smooth Scrolling
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Lazy Loading for Images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

// Add ripple animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Product card hover effects
document.addEventListener('mouseover', function(e) {
    if (e.target.closest('.product-card')) {
        const card = e.target.closest('.product-card');
        card.style.transform = 'translateY(-8px) scale(1.02)';
    }
});

document.addEventListener('mouseout', function(e) {
    if (e.target.closest('.product-card')) {
        const card = e.target.closest('.product-card');
        card.style.transform = 'translateY(0) scale(1)';
    }
});

// Category card click animation
document.addEventListener('click', function(e) {
    if (e.target.closest('.category-card')) {
        const card = e.target.closest('.category-card');
        card.style.transform = 'scale(0.95)';
        setTimeout(() => {
            card.style.transform = '';
        }, 150);
    }
});

// Scroll-based animations
let scrollTimeout;
window.addEventListener('scroll', function() {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;

        // Parallax effect for hero
        const hero = document.querySelector('.hero-section');
        if (hero) {
            hero.style.transform = `translateY(${rate}px)`;
        }
    }, 10);
});

// Loading state management
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
});

// Add loading class to body initially
document.body.classList.add('loading');
