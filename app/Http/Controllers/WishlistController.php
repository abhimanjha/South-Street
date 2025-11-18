<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlist()->with('product.images')->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $existing = Wishlist::where('user_id', auth()->id())
                           ->where('product_id', $request->product_id)
                           ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist'
            ]);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id
        ]);

        $count = Wishlist::where('user_id', auth()->id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist',
            'count' => $count
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        Wishlist::where('user_id', auth()->id())
               ->where('product_id', $request->product_id)
               ->delete();

        $count = Wishlist::where('user_id', auth()->id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist',
            'count' => $count
        ]);
    }

    public function count(): JsonResponse
    {
        $count = Wishlist::where('user_id', auth()->id())->count();

        return response()->json(['count' => $count]);
    }
}
