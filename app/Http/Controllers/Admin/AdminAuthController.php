<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Check if admin exists
        $admin = \App\Models\Admin::where('email', $credentials['email'])->first();
        
        if (!$admin) {
            \Log::info('Admin login failed: Email not found - ' . $credentials['email']);
            return back()->withErrors([
                'email' => 'No admin account found with this email address.',
            ])->withInput($request->only('email'));
        }

        // Check password manually
        if (!\Hash::check($credentials['password'], $admin->password)) {
            \Log::info('Admin login failed: Wrong password for - ' . $credentials['email']);
            return back()->withErrors([
                'email' => 'The password is incorrect.',
            ])->withInput($request->only('email'));
        }

        // Try authentication
        \Log::info('Attempting admin login for: ' . $credentials['email']);
        
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            \Log::info('Admin login successful: ' . $credentials['email']);
            
            // Redirect to intended URL or admin dashboard
            $intended = session()->pull('url.intended', route('admin.dashboard'));
            return redirect($intended)->with('success', 'Welcome to Admin Panel!');
        }

        \Log::error('Admin login failed: Auth attempt failed for - ' . $credentials['email']);
        return back()->withErrors([
            'email' => 'Authentication failed. Please contact support.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}

