<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

// Get the first order
$order = Order::first();

if ($order) {
    echo "Order ID: {$order->id}\n";
    echo "Current Status: {$order->status}\n";
    echo "Delivered At: " . ($order->delivered_at ? $order->delivered_at->format('Y-m-d H:i:s') : 'Not set') . "\n\n";
    
    // Update to delivered status
    $order->status = 'delivered';
    $order->delivered_at = now()->subDays(2); // Delivered 2 days ago
    $order->save();
    
    echo "✓ Order updated!\n";
    echo "New Status: {$order->status}\n";
    echo "Delivered At: {$order->delivered_at->format('Y-m-d H:i:s')}\n";
    echo "Can be returned: " . ($order->canBeReturned() ? 'YES' : 'NO') . "\n";
    echo "Days since delivery: " . now()->diffInDays($order->delivered_at) . "\n";
    echo "\nNow visit: http://localhost/account/orders/{$order->id}\n";
} else {
    echo "No orders found. Please create an order first.\n";
}
