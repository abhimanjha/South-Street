<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        // If already logged in as regular user, redirect to home
        if (auth()->check()) {
            return redirect()->route('home');
        }
        
        return view('front.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = auth()->user();
            
            // Update last login time
            $user->update(['last_login_at' => now()]);
            
            // Send login notifications (wrapped in try-catch to prevent login failures)
            try {
                $notificationService = new \App\Services\NotificationService();
                $notificationService->sendLoginNotifications($user);
            } catch (\Exception $e) {
                // Log the error but don't prevent login
                \Log::error('Failed to send login notifications: ' . $e->getMessage());
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
