<?php
// app/Http/Controllers/ReviewController.php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function test(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Test successful',
            'user' => auth()->user()->name ?? 'Guest',
            'csrf_token' => $request->header('X-CSRF-TOKEN') ? 'Present' : 'Missing'
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            // Check if user already reviewed this product
            $existingReview = Review::where('user_id', auth()->id())
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($existingReview) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already reviewed this product'
                    ], 422);
                }
                return back()->with('error', 'You have already reviewed this product');
            }

            $validated['user_id'] = auth()->id();
            $validated['is_approved'] = false; // Reviews need approval by default

            $review = Review::create($validated);

            // Update product rating
            $this->updateProductRating($validated['product_id']);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Review submitted successfully! It will be visible after approval.',
                    'review' => $review->load('user')
                ]);
            }

            return back()->with('success', 'Review submitted successfully. It will be visible after approval.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Review submission error: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while submitting your review. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while submitting your review. Please try again.');
        }
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