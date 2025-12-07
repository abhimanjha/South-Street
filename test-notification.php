<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Notifications\DiscountNotification;
use Illuminate\Support\Facades\Notification;

echo "Testing Notification System...\n\n";

// Get all users
$users = User::all();
echo "Found " . $users->count() . " users\n";

if ($users->count() === 0) {
    echo "No users found! Please create a user first.\n";
    exit;
}

// Send test notification
echo "Sending test notification...\n";

Notification::send($users, new DiscountNotification(
    'Test Notification',
    'This is a test notification to verify the system works!',
    'TEST20',
    20,
    now()->addDays(7)->format('Y-m-d')
));

echo "✓ Notification sent!\n\n";

// Check database
$notificationCount = DB::table('notifications')->count();
echo "Total notifications in database: " . $notificationCount . "\n";

// Show recent notifications
$recent = DB::table('notifications')
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get();

echo "\nRecent notifications:\n";
foreach ($recent as $notif) {
    $data = json_decode($notif->data, true);
    echo "- " . ($data['title'] ?? 'No title') . " (Created: " . $notif->created_at . ")\n";
}

echo "\n✓ Test complete! Now login and visit the site to see the notification.\n";
