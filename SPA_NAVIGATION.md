# SPA Navigation System

## Overview
The SPA (Single Page Application) navigation system prevents the navbar from refreshing when navigating between pages. Only the main content area updates, providing a smoother user experience.

## How It Works

### 1. **Automatic Link Interception**
- All internal links are automatically intercepted
- Navigation happens via AJAX instead of full page reload
- Browser back/forward buttons work normally

### 2. **What Gets Updated**
- Main content area (`<main>` tag)
- Page title
- Meta description
- Cart and wishlist counts
- Browser URL (using History API)

### 3. **What Stays Persistent**
- Navbar (no refresh)
- Footer (no refresh)
- Notification system
- All header scripts and styles

## Features

### Loading Indicator
- Smooth gold progress bar at the top
- Appears during page transitions
- Automatically hides when content loads

### Smooth Animations
- Content fades in smoothly
- No jarring transitions
- Scroll to top on navigation

### Error Handling
- Falls back to normal navigation if AJAX fails
- Works with all modern browsers
- Graceful degradation for older browsers

## Disabling SPA Navigation

If you need a link to use normal navigation (full page reload), add the `no-spa` class:

```html
<a href="/some-page" class="no-spa">Normal Navigation</a>
```

## Browser Compatibility
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- IE11: ⚠️ Falls back to normal navigation

## Files Added
1. `public/js/spa-navigation.js` - Main SPA logic
2. `public/css/spa-navigation.css` - Loading indicator styles
3. Updated `resources/views/layouts/app.blade.php` - Integration

## Benefits
- ✅ Navbar never refreshes
- ✅ Faster page transitions
- ✅ Better user experience
- ✅ Maintains scroll position on navbar
- ✅ Cart/wishlist counts update automatically
- ✅ Works with browser back/forward buttons
