// Main Application JavaScript
// Bootstrap-based application without Vite

document.addEventListener('DOMContentLoaded', function() {
    console.log('App.js loaded successfully');
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Initialize any Bootstrap components if needed
    if (typeof bootstrap !== 'undefined') {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Initialize popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }
    
    // Custom tailoring page enhancements
    if (document.body.classList.contains('custom-tailoring-page')) {
        // Add any custom tailoring specific JavaScript here
        console.log('Custom tailoring page loaded');
    }
});

// Export for global use if needed
window.App = {
    init: function() {
        console.log('App initialized');
    }
};