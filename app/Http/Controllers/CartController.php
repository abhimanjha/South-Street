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
        try {
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

        // Handle variant_id: convert empty string to null
        $variantId = $request->variant_id ?: null;

        // Check if variant exists and adjust price
        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
                $price += $variant->price_adjustment ?? 0;
        }

        // Check if item already exists in cart
            $existingItemQuery = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id);

            if ($variantId) {
                $existingItemQuery->where('product_variant_id', $variantId);
            } else {
                $existingItemQuery->whereNull('product_variant_id');
            }

            $existingItem = $existingItemQuery->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $variantId,
                'quantity' => $request->quantity,
                'price' => $price
            ]);
        }

            // Refresh cart and load items to get updated count
        $cart->refresh();
            $cart->load('items');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cart->item_count
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart. Please try again.'
            ], 500);
        }
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
        try {
        $userId = auth()->id();
        $sessionId = session()->getId();

            $cartQuery = Cart::query();

        if ($userId) {
                $cartQuery->where('user_id', $userId);
        } else {
                $cartQuery->where('session_id', $sessionId)->whereNull('user_id');
        }

            $cart = $cartQuery->with('items')->first();
        $count = $cart ? $cart->item_count : 0;

        return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            \Log::error('Cart count error: ' . $e->getMessage());
            return response()->json(['count' => 0], 500);
        }
    }
}
