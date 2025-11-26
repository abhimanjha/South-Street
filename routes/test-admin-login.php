<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Temporary test route - DELETE THIS AFTER TESTING
Route::get('/test-admin-login', function () {
    $admin = App\Models\Admin::where('email', 'admin@southstreet.com')->first();
    
    if (!$admin) {
        return 'Admin not found!';
    }
    
    $passwordCheck = Hash::check('admin@123', $admin->password);
    
    $attemptResult = Auth::guard('admin')->attempt([
        'email' => 'admin@southstreet.com',
        'password' => 'admin@123'
    ]);
    
    if ($attemptResult) {
        return redirect()->route('admin.dashboard')->with('success', 'Test login successful!');
    }
    
    return 'Login attempt failed! Password check: ' . ($passwordCheck ? 'PASS' : 'FAIL');
});
