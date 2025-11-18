<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user with email: admin@gmail.com and password: admin@123';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if admin already exists
        $admin = Admin::where('email', 'admin@gmail.com')->first();
        
        if ($admin) {
            $this->info('Admin user already exists. Updating password...');
            $admin->update([
                'password' => Hash::make('admin@123'),
            ]);
            $this->info('Admin password updated successfully!');
        } else {
            Admin::create([
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin@123'),
                'email_verified_at' => now()
            ]);
            $this->info('Admin user created successfully!');
            $this->info('Email: admin@gmail.com');
            $this->info('Password: admin@123');
        }
        
        return 0;
    }
}
