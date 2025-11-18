<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomTailoringRequest;
use App\Notifications\CustomTailoringStatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CustomTailoringController extends Controller
{
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
            'work_status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $customTailoring->status;
        $customTailoring->update($validated);

        // Send notification to user if status changed
        if ($oldStatus !== $customTailoring->status && $customTailoring->user) {
            $customTailoring->user->notify(new CustomTailoringStatusUpdate($customTailoring));
        }

        return redirect()->back()->with('success', 'Request status updated successfully!');
    }
}
