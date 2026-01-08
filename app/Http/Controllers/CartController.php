<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;

class CartController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Cart::with('items.product');

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }

        $cart = $query->firstOrCreate(
            $userId ? ['user_id' => $userId] : ['session_id' => $sessionId]
        );

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $userId = auth()->id();
        $sessionId = session()->getId();

        $cart = Cart::firstOrCreate(
            $userId ? ['user_id' => $userId] : ['session_id' => $sessionId]
        );

        $product = Product::findOrFail($request->product_id);
        $price = $product->price;

        // Check if variant exists and adjust price
        if ($request->variant_id) {
            $variant = ProductVariant::findOrFail($request->variant_id);
            $price += $variant->price_adjustment;
        }

        // Check if item already exists in cart
        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $request->variant_id)
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'price' => $price
            ]);
        }

        // Refresh cart to get updated count
        $cart->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cart->item_count
        ]);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'item_total' => $cartItem->subtotal,
            'cart_total' => $cartItem->cart->total
        ]);
    }

    public function remove(CartItem $cartItem)
    {
        $cart = $cartItem->cart; // Get cart before deleting item
        $cartItem->delete();
        $cart->refresh(); // Refresh cart to calculate new totals

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!',
            'cart_total' => $cart->total
        ]);
    }

    public function count()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $cart = Cart::query();

        if ($userId) {
            $cart->where('user_id', $userId);
        } else {
            $cart->where('session_id', $sessionId)->whereNull('user_id');
        }

        $cart = $cart->first();
        $count = $cart ? $cart->item_count : 0;

        return response()->json(['count' => $count]);
    }
}
