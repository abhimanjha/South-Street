<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->with('category', 'images', 'variants')
            ->latest()
            ->take(8)
            ->get();

        $trendingProducts = Product::where('is_trending', true)
            ->with('category', 'images', 'variants')
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = Product::with('category', 'images', 'variants')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all();

        $blogPosts = BlogPost::latest()
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'trendingProducts',
            'newArrivals',
            'categories',
            'blogPosts'
        ));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Here you can add logic to send email or save to database
        // For now, just redirect with success message

        return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    public function faq()
    {
        return view('faq');
    }

    public function termsOfService()
    {
        return view('terms-of-service');
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    public function returnPolicy()
    {
        return view('return-policy');
    }

    public function refundPolicy()
    {
        return view('refund-policy');
    }

    public function shippingPolicy()
    {
        return view('shipping-policy');
    }

    public function gallery()
    {
        $products = Product::with('images')->latest()->take(20)->get();
        return view('gallery', compact('products'));
    }
}
