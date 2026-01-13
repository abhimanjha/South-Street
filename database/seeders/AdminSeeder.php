<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        // Since Admin model uses 'hashed' cast, we can set password directly
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@southstreet.com',
            'password' => 'admin@123',
            'email_verified_at' => now(),
        ]);

        echo "Admin user created successfully!\n";
        echo "Email: admin@southstreet.com\n";
        echo "Password: admin@123\n";
    }
}
