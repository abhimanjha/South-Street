<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SystemNotification;

class NotificationService
{
    /**
     * Send welcome notification on login
     */
    public function sendWelcomeNotification(User $user)
    {
        $user->notify(new SystemNotification([
            'type' => 'success',
            'title' => 'Welcome Back!',
            'message' => "Hi {$user->name}, great to see you again! Check out our latest arrivals.",
        ]));
    }

    /**
     * Send active offers notification
     */
    public function sendActiveOffersNotification(User $user)
    {
        // Check if there are any active coupons
        $activeCoupon = \App\Models\Coupon::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();

        if ($activeCoupon) {
            $user->notify(new SystemNotification([
                'type' => 'discount',
                'title' => '🎉 Special Offer Available!',
                'message' => "Get {$activeCoupon->discount_percentage}% OFF on your next purchase!",
                'discount_code' => $activeCoupon->code,
                'discount_percentage' => $activeCoupon->discount_percentage,
            ]));
        }
    }

    /**
     * Send new arrivals notification
     */
    public function sendNewArrivalsNotification(User $user)
    {
        // Get products added in last 7 days
        $newProductsCount = Product::where('created_at', '>=', now()->subDays(7))
            ->where('is_active', true)
            ->count();

        if ($newProductsCount > 0) {
            $user->notify(new SystemNotification([
                'type' => 'info',
                'title' => '✨ New Arrivals!',
                'message' => "{$newProductsCount} new products just arrived. Be the first to check them out!",
            ]));
        }
    }

    /**
     * Send low stock alert notification
     */
    public function sendLowStockNotification(User $user)
    {
        // Get products in user's wishlist that are low in stock
        $lowStockProducts = $user->wishlist()
            ->wherePivot('created_at', '>=', now()->subDays(30))
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 5)
            ->get();

        if ($lowStockProducts->isNotEmpty()) {
            $product = $lowStockProducts->first();
            $user->notify(new SystemNotification([
                'type' => 'warning',
                'title' => '⚠️ Hurry Up! Low Stock Alert',
                'message' => "'{$product->name}' from your wishlist is running low on stock. Only {$product->stock_quantity} left!",
            ]));
        }
    }

    /**
     * Send cart reminder notification
     */
    public function sendCartReminderNotification(User $user)
    {
        $cartItemsCount = $user->cartItems()->count();
        
        if ($cartItemsCount > 0) {
            $user->notify(new SystemNotification([
                'type' => 'info',
                'title' => '🛒 Items Waiting in Your Cart',
                'message' => "You have {$cartItemsCount} item(s) in your cart. Complete your purchase now!",
            ]));
        }
    }

    /**
     * Send all login notifications
     */
    public function sendLoginNotifications(User $user)
    {
        // Send welcome notification immediately
        $this->sendWelcomeNotification($user);

        // Send other notifications with slight delays
        $this->sendActiveOffersNotification($user);
        $this->sendNewArrivalsNotification($user);
        $this->sendLowStockNotification($user);
        
        // Only send cart reminder if user hasn't logged in for more than 1 day
        if ($user->last_login_at && $user->last_login_at->lt(now()->subDay())) {
            $this->sendCartReminderNotification($user);
        }
    }

    /**
     * Notify all users about a new product
     */
    public function notifyNewProduct(Product $product)
    {
        $users = User::where('created_at', '>=', now()->subMonths(3))->get();
        
        foreach ($users as $user) {
            $user->notify(new SystemNotification([
                'type' => 'info',
                'title' => '🆕 New Product Alert!',
                'message' => "Check out our latest addition: {$product->name}",
            ]));
        }
    }

    /**
     * Notify users about flash sale
     */
    public function notifyFlashSale($discountPercentage, $code = null)
    {
        $users = User::all();
        
        foreach ($users as $user) {
            $user->notify(new SystemNotification([
                'type' => 'offer',
                'title' => '⚡ Flash Sale Alert!',
                'message' => "Limited time offer! Get {$discountPercentage}% OFF on all products!",
                'discount_code' => $code,
                'discount_percentage' => $discountPercentage,
            ]));
        }
    }
}
