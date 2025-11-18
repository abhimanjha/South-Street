<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } else {
                $query->where('is_approved', false);
            }
        }

        $reviews = $query->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);

        // Update product rating
        $product = $review->product;
        $approvedReviews = $product->reviews()->where('is_approved', true)->get();
        
        if ($approvedReviews->count() > 0) {
            $averageRating = $approvedReviews->avg('rating');
            $product->update([
                'average_rating' => round($averageRating, 2),
                'total_reviews' => $approvedReviews->count()
            ]);
        }

        return back()->with('success', 'Review status updated');
    }

    public function destroy(Review $review)
    {
        $productId = $review->product_id;
        $review->delete();

        // Update product rating
        $product = Product::find($productId);
        $approvedReviews = $product->reviews()->where('is_approved', true)->get();
        
        $product->update([
            'average_rating' => $approvedReviews->avg('rating') ?? 0,
            'total_reviews' => $approvedReviews->count()
        ]);

        return back()->with('success', 'Review deleted successfully');
    }
}