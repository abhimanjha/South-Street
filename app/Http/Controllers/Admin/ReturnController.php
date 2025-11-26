<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = OrderReturn::with(['order', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.returns.index', compact('returns'));
    }

    public function show(OrderReturn $return)
    {
        $return->load(['order.items.product', 'order.address', 'user']);

        return view('admin.returns.show', compact('return'));
    }

    public function updateStatus(Request $request, OrderReturn $return)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,picked_up,received,refund_processed,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $return->status = $validated['status'];
        $return->admin_notes = $validated['admin_notes'] ?? $return->admin_notes;

        // Update timestamps based on status
        switch ($validated['status']) {
            case 'approved':
                $return->approved_at = now();
                break;
            case 'picked_up':
                $return->picked_up_at = now();
                break;
            case 'received':
                $return->received_at = now();
                // Auto-process refund within 24 hours
                break;
            case 'refund_processed':
                $return->refund_processed_at = now();
                break;
            case 'completed':
                $return->completed_at = now();
                break;
        }

        $return->save();

        return back()->with('success', 'Return status updated successfully.');
    }
}
