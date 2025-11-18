<?php
// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated using admin guard
        if (!Auth::guard('admin')->check()) {
            // Store intended URL for redirect after login
            if (!$request->expectsJson()) {
                session()->put('url.intended', $request->fullUrl());
            }
            return redirect()->route('admin.login')->with('error', 'Please login to access the admin panel.');
        }

        return $next($request);
    }
}

