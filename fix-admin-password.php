<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

$admin = Admin::first();

if ($admin) {
    $admin->password = Hash::make('admin@123');
    $admin->save();
    echo "✓ Admin password updated successfully!\n";
    echo "Email: {$admin->email}\n";
    echo "Password: admin@123\n";
} else {
    echo "✗ No admin found. Creating new admin...\n";
    Admin::create([
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('admin@123'),
        'email_verified_at' => now(),
    ]);
    echo "✓ Admin created successfully!\n";
    echo "Email: admin@gmail.com\n";
    echo "Password: admin@123\n";
}
