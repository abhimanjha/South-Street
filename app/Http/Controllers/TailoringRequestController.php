<?php

namespace App\Http\Controllers;

use App\Models\TailoringRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTailoringRequest;

class TailoringRequestController extends Controller
{
    // User form
    public function create()
    {
        return view('tailoring.form');
    }

    // Store user request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cloth_material' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'style_type' => 'required|string|max:255',
            'size_details' => 'required|string',
            'additional_notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $tailoringRequest = TailoringRequest::create($validated);

        // Notify admin
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new NewTailoringRequest($tailoringRequest));
        }

        return redirect()->back()->with('success', 'Your tailoring request has been submitted successfully!');
    }

    // User view their requests
    public function myRequests()
    {
        $requests = auth()->user()->tailoringRequests()->latest()->paginate(10);
        return view('tailoring.my-requests', compact('requests'));
    }

    // Admin list
    public function adminIndex()
    {
        $requests = TailoringRequest::latest()->paginate(20);
        return view('admin.tailoring.index', compact('requests'));
    }

    // Admin update status
    public function adminUpdateStatus(Request $request, TailoringRequest $tailoringRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,In Progress,Completed',
        ]);

        $tailoringRequest->update($validated);

        return redirect()->back()->with('success', 'Request status updated successfully!');
    }
}
