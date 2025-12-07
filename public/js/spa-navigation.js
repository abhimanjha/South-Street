// SPA Navigation - Prevents navbar refresh on page changes
(function() {
    'use strict';

    // Check if browser supports History API
    if (!window.history || !window.history.pushState) {
        return; // Fallback to normal navigation
    }

    let isNavigating = false;

    // Initialize SPA navigation
    function initSPANavigation() {
        // Intercept all internal link clicks
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            
            // Check if it's a valid internal link
            if (!link || !link.href) return;
            if (link.target === '_blank') return;
            if (link.hasAttribute('download')) return;
            if (link.href.includes('#')) return;
            if (link.classList.contains('no-spa')) return;
            
            const url = new URL(link.href);
            
            // Only handle same-origin links
            if (url.origin !== window.location.origin) return;
            
            // Prevent default navigation
            e.preventDefault();
            
            // Navigate using AJAX
            navigateToPage(link.href);
        });

        // Intercept form submissions (like search form)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            
            // Skip if form has no-spa class
            if (form.classList.contains('no-spa')) return;
            
            // Skip if form has target attribute
            if (form.target && form.target !== '_self') return;
            
            // Skip if form method is POST (only handle GET forms like search)
            const method = (form.method || 'GET').toUpperCase();
            if (method !== 'GET') return;
            
            // Prevent default form submission
            e.preventDefault();
            
            // Build URL with form data
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const action = form.action || window.location.href;
            const url = action + (action.includes('?') ? '&' : '?') + params.toString();
            
            // Navigate using AJAX
            navigateToPage(url);
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.url) {
                navigateToPage(e.state.url, false);
            }
        });

        // Save initial state
        history.replaceState({ url: window.location.href }, '', window.location.href);
    }

    // Navigate to a page using AJAX
    function navigateToPage(url, pushState = true) {
        if (isNavigating) return;
        isNavigating = true;

        // Show loading indicator
        showLoadingIndicator();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-SPA-Navigation': 'true'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Navigation failed');
            }
            return response.text();
        })
        .then(html => {
            // Parse the HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update the main content
            const newMain = doc.querySelector('main');
            const currentMain = document.querySelector('main');
            
            if (newMain && currentMain) {
                currentMain.innerHTML = newMain.innerHTML;
                
                // Update page title
                const newTitle = doc.querySelector('title');
                if (newTitle) {
                    document.title = newTitle.textContent;
                }
                
                // Update meta description
                const newMetaDesc = doc.querySelector('meta[name="description"]');
                const currentMetaDesc = document.querySelector('meta[name="description"]');
                if (newMetaDesc && currentMetaDesc) {
                    currentMetaDesc.setAttribute('content', newMetaDesc.getAttribute('content'));
                }
                
                // Update cart and wishlist counts
                updateCartCount();
                if (typeof updateWishlistCount === 'function') {
                    updateWishlistCount();
                }
                
                // Execute any inline scripts in the new content
                executeScripts(newMain);
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Update URL if needed
                if (pushState) {
                    history.pushState({ url: url }, '', url);
                }
                
                // Trigger custom event for other scripts
                window.dispatchEvent(new CustomEvent('spa-navigated', { detail: { url: url } }));
            } else {
                // Fallback to full page reload
                window.location.href = url;
            }
        })
        .catch(error => {
            console.error('SPA Navigation error:', error);
            // Fallback to full page reload on error
            window.location.href = url;
        })
        .finally(() => {
            hideLoadingIndicator();
            isNavigating = false;
        });
    }

    // Execute scripts from loaded content
    function executeScripts(container) {
        const scripts = container.querySelectorAll('script');
        scripts.forEach(script => {
            if (script.src) {
                // External script
                const newScript = document.createElement('script');
                newScript.src = script.src;
                document.body.appendChild(newScript);
            } else {
                // Inline script
                try {
                    eval(script.textContent);
                } catch (e) {
                    console.error('Error executing inline script:', e);
                }
            }
        });
    }

    // Show loading indicator
    function showLoadingIndicator() {
        let loader = document.getElementById('spa-loader');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'spa-loader';
            loader.innerHTML = '<div class="spa-loader-bar"></div>';
            document.body.appendChild(loader);
        }
        loader.classList.add('active');
    }

    // Hide loading indicator
    function hideLoadingIndicator() {
        const loader = document.getElementById('spa-loader');
        if (loader) {
            loader.classList.remove('active');
        }
    }

    // Update cart count function (make it global)
    window.updateCartCount = function() {
        const cartCountUrl = document.querySelector('[data-cart-count-url]');
        if (cartCountUrl) {
            fetch(cartCountUrl.dataset.cartCountUrl)
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('cart-count');
                    if (badge) {
                        badge.textContent = data.count;
                    }
                })
                .catch(err => console.error('Error updating cart count:', err));
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSPANavigation);
    } else {
        initSPANavigation();
    }
})();
