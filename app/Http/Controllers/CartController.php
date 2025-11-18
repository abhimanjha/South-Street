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
        $cart = Cart::with('items.product')->firstOrCreate([
            'user_id' => auth()->id()
        ]);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id()
        ]);

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
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!',
            'cart_total' => $cartItem->cart->total
        ]);
    }

    public function count()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        $count = $cart ? $cart->item_count : 0;

        return response()->json(['count' => $count]);
    }
}
