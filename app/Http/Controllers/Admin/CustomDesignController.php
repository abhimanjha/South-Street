<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomDesign;
use Illuminate\Http\Request;

class CustomDesignController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomDesign::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $designs = $query->latest()->paginate(20);

        return view('admin.custom-designs.index', compact('designs'));
    }

    public function show(CustomDesign $customDesign)
    {
        $customDesign->load('user');
        return view('admin.custom-designs.show', compact('customDesign'));
    }

    public function updateStatus(Request $request, CustomDesign $customDesign)
    {
        $validated = $request->validate([
            'status' => 'required|in:submitted,under_review,approved,rejected,in_production,completed',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $customDesign->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $customDesign->admin_notes
        ]);

        return back()->with('success', 'Custom design status updated successfully');
    }

    public function destroy(CustomDesign $customDesign)
    {
        $customDesign->delete();
        return redirect()->route('admin.custom-designs.index')
            ->with('success', 'Custom design deleted successfully');
    }
}
