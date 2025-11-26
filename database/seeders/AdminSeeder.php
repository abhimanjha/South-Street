<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@southstreet.com',
            'password' => Hash::make('admin@123'),
            'email_verified_at' => now(),
        ]);

        echo "Admin user created successfully!\n";
        echo "Email: admin@southstreet.com\n";
        echo "Password: admin@123\n";
    }
}
