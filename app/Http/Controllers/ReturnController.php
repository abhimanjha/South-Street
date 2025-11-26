<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReturnController extends Controller
{
    public function create(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if order can be returned
        if (!$order->canBeReturned()) {
            return redirect()->route('account.orders.show', $order)
                ->with('error', 'This order cannot be returned. Returns are only available within 7 days of delivery.');
        }

        // Check if there's already an active return
        if ($order->hasActiveReturn()) {
            return redirect()->route('account.orders.show', $order)
                ->with('error', 'A return request already exists for this order.');
        }

        return view('returns.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if order can be returned
        if (!$order->canBeReturned()) {
            return back()->with('error', 'This order cannot be returned.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048',
            'bank_account' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
            'account_holder_name' => 'required|string|max:100',
        ]);

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('returns', 'public');
                $images[] = $path;
            }
        }

        // Create return request
        $return = OrderReturn::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'return_number' => 'RET-' . strtoupper(Str::random(10)),
            'type' => 'return',
            'status' => 'requested',
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'images' => $images,
            'refund_amount' => $order->total ?? 0,
            'bank_account' => $validated['bank_account'],
            'ifsc_code' => $validated['ifsc_code'],
            'account_holder_name' => $validated['account_holder_name'],
            'requested_at' => now(),
        ]);

        return redirect()->route('returns.show', $return)
            ->with('success', 'Return request submitted successfully. We will review it shortly.');
    }

    public function show(OrderReturn $return)
    {
        // Check if user owns this return
        if ($return->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $return->load(['order.items.product', 'order.address']);

        return view('returns.show', compact('return'));
    }

    public function index()
    {
        $returns = OrderReturn::where('user_id', auth()->id())
            ->with('order')
            ->latest()
            ->paginate(10);

        return view('returns.index', compact('returns'));
    }
}
