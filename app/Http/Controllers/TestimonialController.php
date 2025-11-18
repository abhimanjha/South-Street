<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('approved', true)->get();
        return view('testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'story' => 'required|string|max:1000',
            'video_url' => 'nullable|url',
        ]);

        Testimonial::create([
            'user_id' => Auth::id(),
            'story' => $request->story,
            'video_url' => $request->video_url,
        ]);

        return redirect()->back()->with('success', 'Testimonial submitted for approval.');
    }
}
