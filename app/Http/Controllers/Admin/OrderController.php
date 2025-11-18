<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'address', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled,returned,out_of_stock',
            'tracking_number' => 'nullable|string',
            'courier_service' => 'nullable|string',
            'admin_notes' => 'nullable|string'
        ]);

        $updateData = ['status' => $validated['status']];

        if (isset($validated['tracking_number'])) {
            $updateData['tracking_number'] = $validated['tracking_number'];
        }

        if (isset($validated['courier_service'])) {
            $updateData['courier_service'] = $validated['courier_service'];
        }

        if (isset($validated['admin_notes'])) {
            $updateData['admin_notes'] = $validated['admin_notes'];
        }

        // Handle out of stock - restore stock if order is cancelled or out of stock
        if (in_array($validated['status'], ['cancelled', 'out_of_stock'])) {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = \App\Models\ProductVariant::find($item->variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                } else {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);
                    }
                }
            }
        }

        // Set timestamps based on status
        if ($validated['status'] === 'confirmed' && !$order->confirmed_at) {
            $updateData['confirmed_at'] = now();
        } elseif ($validated['status'] === 'shipped' && !$order->shipped_at) {
            $updateData['shipped_at'] = now();
        } elseif ($validated['status'] === 'delivered' && !$order->delivered_at) {
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);

        return back()->with('success', 'Order status updated successfully');
    }
}