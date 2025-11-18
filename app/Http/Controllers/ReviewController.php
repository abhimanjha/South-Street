<?php
// app/Http/Controllers/ReviewController.php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048'
        ]);

        // Check if user has purchased this product
        $order = Order::where('id', $validated['order_id'])
            ->where('user_id', auth()->id())
            ->whereHas('items', function($query) use ($validated) {
                $query->where('product_id', $validated['product_id']);
            })
            ->first();

        if (!$order) {
            return back()->with('error', 'You can only review products you have purchased');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->where('order_id', $validated['order_id'])
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product');
        }

        $validated['user_id'] = auth()->id();
        
        // Handle images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('reviews', 'public');
            }
            $validated['images'] = $images;
        }

        $review = Review::create($validated);

        // Update product rating
        $this->updateProductRating($validated['product_id']);

        return back()->with('success', 'Review submitted successfully. It will be visible after approval.');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('images')) {
            $images = $review->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('reviews', 'public');
            }
            $validated['images'] = $images;
        }

        $review->update($validated);

        // Update product rating
        $this->updateProductRating($review->product_id);

        return back()->with('success', 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $productId = $review->product_id;
        $review->delete();

        // Update product rating
        $this->updateProductRating($productId);

        return back()->with('success', 'Review deleted successfully');
    }

    public function markHelpful(Review $review)
    {
        $review->increment('helpful_count');

        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count
        ]);
    }

    private function updateProductRating($productId)
    {
        $product = Product::find($productId);
        $approvedReviews = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->get();

        if ($approvedReviews->count() > 0) {
            $averageRating = $approvedReviews->avg('rating');
            $product->update([
                'average_rating' => round($averageRating, 2),
                'total_reviews' => $approvedReviews->count()
            ]);
        } else {
            $product->update([
                'average_rating' => 0,
                'total_reviews' => 0
            ]);
        }
    }
}