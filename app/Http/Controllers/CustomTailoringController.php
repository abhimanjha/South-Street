<?php

namespace App\Http\Controllers;

use App\Models\CustomTailoringRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewCustomTailoringRequest;

class CustomTailoringController extends Controller
{
    public function create()
    {
        return view('front.custom-tailoring');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cloth_material' => 'required|string|max:255',
            'sizes' => 'required|array',
            'color' => 'required|string|max:255',
            'style' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $tailoringRequest = CustomTailoringRequest::create($validated);

        // Notify admin
        $admin = \App\Models\User::where('is_admin', true)->first();
        if ($admin) {
            $admin->notify(new NewCustomTailoringRequest($tailoringRequest));
        }

        return redirect()->back()->with('success', 'Your custom tailoring request has been submitted successfully!');
    }

    public function index()
    {
        $requests = CustomTailoringRequest::latest()->paginate(20);
        return view('admin.custom-tailoring.index', compact('requests'));
    }

    public function show(CustomTailoringRequest $customTailoring)
    {
        return view('admin.custom-tailoring.show', compact('customTailoring'));
    }

    public function updateStatus(Request $request, CustomTailoringRequest $customTailoring)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
            'work_status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customTailoring->update($validated);

        return redirect()->back()->with('success', 'Request status updated successfully!');
    }
}
