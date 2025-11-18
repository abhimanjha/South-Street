<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms-of-service', [HomeController::class, 'termsOfService'])->name('terms');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy');
Route::get('/return-policy', [HomeController::class, 'returnPolicy'])->name('return-policy');
Route::get('/refund-policy', [HomeController::class, 'refundPolicy'])->name('refund-policy');
Route::get('/shipping-policy', [HomeController::class, 'shippingPolicy'])->name('shipping-policy');

// New Features
Route::get('/testimonials', [App\Http\Controllers\TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimonials', [App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');

// Tailoring Requests
Route::get('/custom-tailoring', [App\Http\Controllers\TailoringRequestController::class, 'create'])->name('tailoring.create');
Route::post('/custom-tailoring', [App\Http\Controllers\TailoringRequestController::class, 'store'])->name('tailoring.store');
Route::middleware(['auth'])->group(function () {
    Route::get('/my-tailoring-requests', [App\Http\Controllers\TailoringRequestController::class, 'myRequests'])->name('tailoring.my-requests');
});

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/compare', [ProductController::class, 'compare'])->name('products.compare');
Route::post('/products/compare/add', [ProductController::class, 'compareAdd'])->name('products.compare.add');
Route::post('/products/compare/remove', [ProductController::class, 'compareRemove'])->name('products.compare.remove');
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/products/eco', [ProductController::class, 'eco'])->name('products.eco');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');


// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');

    Route::post('/checkout/payment/success', [CheckoutController::class, 'paymentSuccess'])->name('checkout.payment.success');
    Route::get('/checkout/payment/failed', [CheckoutController::class, 'paymentFailed'])->name('checkout.payment.failed');
    Route::get('/order/success/{order}', [CheckoutController::class, 'orderSuccess'])->name('order.success');

// Razorpay routes
    Route::get('/razorpay', [App\Http\Controllers\RazorpayController::class, 'index'])->name('razorpay.index');
    Route::post('/razorpay/create-order', [App\Http\Controllers\RazorpayController::class, 'createOrder'])->name('razorpay.createOrder');
    Route::post('/razorpay/create-buy-now-order', [App\Http\Controllers\RazorpayController::class, 'createBuyNowOrder'])->name('razorpay.createBuyNowOrder');
    Route::post('/razorpay/verify-payment', [App\Http\Controllers\RazorpayController::class, 'verifyPayment'])->name('razorpay.verify');
    Route::get('/razorpay/success/{order}', [App\Http\Controllers\RazorpayController::class, 'successPage'])->name('razorpay.success');
    Route::post('/razorpay/webhook', [App\Http\Controllers\RazorpayController::class, 'handleWebhook'])->name('razorpay.webhook');
});

// Wishlist
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
});

// Reviews
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/helpful', [ReviewController::class, 'markHelpful'])->name('reviews.helpful');
});



// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blogPost}', [BlogController::class, 'show'])->name('blog.show');

// User Account
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderShow'])->name('orders.show');
    Route::get('/orders/{order}/track', [AccountController::class, 'orderTrack'])->name('orders.track');
    Route::post('/orders/{order}/cancel', [AccountController::class, 'orderCancel'])->name('orders.cancel');

    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'profileUpdate'])->name('profile.update');
    Route::put('/password', [AccountController::class, 'passwordUpdate'])->name('password.update');

    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'addressStore'])->name('addresses.store');
    Route::put('/addresses/{address}', [AccountController::class, 'addressUpdate'])->name('addresses.update');
    Route::put('/addresses/{address}/set-default', [AccountController::class, 'addressSetDefault'])->name('addresses.set-default');
    Route::delete('/addresses/{address}', [AccountController::class, 'addressDelete'])->name('addresses.delete');

    Route::get('/notifications', [AccountController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [AccountController::class, 'markNotificationRead'])->name('notifications.read');


});

// Admin Routes - Protected by AdminMiddleware (checks admin guard)
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', Admin\ProductController::class);
    Route::delete('products/{product}/images/{image}', [Admin\ProductController::class, 'deleteImage'])->name('products.images.delete');
    Route::post('products/{product}/update-stock', [Admin\ProductController::class, 'updateStock'])->name('products.update-stock');
    Route::post('products/{product}/mark-out-of-stock', [Admin\ProductController::class, 'markOutOfStock'])->name('products.mark-out-of-stock');
    Route::post('products/{product}/mark-in-stock', [Admin\ProductController::class, 'markInStock'])->name('products.mark-in-stock');
    
    // Categories
    Route::resource('categories', Admin\CategoryController::class);
    
    // Orders
    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    


    // Customers
    Route::get('customers', [Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [Admin\CustomerController::class, 'show'])->name('customers.show');

    // Reviews
    Route::get('reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::put('reviews/{review}/approve', [Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Coupons
    Route::resource('coupons', Admin\CouponController::class);

    // Blog
    Route::resource('blog', Admin\BlogController::class);

    // Media
    Route::get('media', [Admin\MediaController::class, 'index'])->name('media.index');
    Route::post('media/upload', [Admin\MediaController::class, 'upload'])->name('media.upload');
    Route::delete('media/{media}', [Admin\MediaController::class, 'destroy'])->name('media.destroy');

    // Settings
    Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');


    Route::get('/pages', [Admin\PageController::class, 'index'])->name('pages.index');

    // Tailoring Requests Management
    Route::get('/tailoring-requests', [App\Http\Controllers\TailoringRequestController::class, 'adminIndex'])->name('admin.tailoring.index');
    Route::put('/tailoring-requests/{tailoringRequest}/status', [App\Http\Controllers\TailoringRequestController::class, 'adminUpdateStatus'])->name('admin.tailoring.update-status');

    // Custom Designs Management
    Route::get('/custom-designs', [Admin\CustomDesignController::class, 'index'])->name('custom-designs.index');
    Route::get('/custom-designs/{customDesign}', [Admin\CustomDesignController::class, 'show'])->name('custom-designs.show');
    Route::put('/custom-designs/{customDesign}/status', [Admin\CustomDesignController::class, 'updateStatus'])->name('custom-designs.update-status');
    Route::delete('/custom-designs/{customDesign}', [Admin\CustomDesignController::class, 'destroy'])->name('custom-designs.destroy');

    // Custom Tailoring Requests Management
    Route::get('/custom-tailoring', [App\Http\Controllers\Admin\CustomTailoringController::class, 'index'])->name('custom-tailoring.index');
    Route::get('/custom-tailoring/{customTailoring}', [App\Http\Controllers\Admin\CustomTailoringController::class, 'show'])->name('custom-tailoring.show');
    Route::put('/custom-tailoring/{customTailoring}/status', [App\Http\Controllers\Admin\CustomTailoringController::class, 'updateStatus'])->name('custom-tailoring.update-status');

    // Custom Tailoring Requests Management
    Route::get('/custom-tailoring', [App\Http\Controllers\Admin\CustomTailoringController::class, 'index'])->name('custom-tailoring.index');
    Route::get('/custom-tailoring/{customTailoring}', [App\Http\Controllers\Admin\CustomTailoringController::class, 'show'])->name('custom-tailoring.show');
    Route::put('/custom-tailoring/{customTailoring}/status', [App\Http\Controllers\Admin\CustomTailoringController::class, 'updateStatus'])->name('custom-tailoring.update-status');

    // Notifications Management
    Route::get('/notifications/create-discount', [App\Http\Controllers\Admin\NotificationController::class, 'createDiscount'])->name('notifications.create-discount');
    Route::post('/notifications/send-discount', [App\Http\Controllers\Admin\NotificationController::class, 'sendDiscount'])->name('notifications.send-discount');

});
// Admin Login Routes (before admin routes - no auth required)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login.submit');
});
Route::post('/admin/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

// Regular User Login Routes
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest');
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('success', 'You have been logged out successfully.');
})->middleware('auth')->name('logout');


