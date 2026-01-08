<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\VirtualPreview;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'category', 'variants'])->active();

        // Apply filters
        $filter = $request->get('filter');
        switch ($filter) {
            case 'new':
                $query->newArrivals();
                break;
            case 'featured':
                $query->featured();
                break;
            case 'discount':
                $query->whereNotNull('discount_price');
                break;
            case 'trending':
                $query->trending();
                break;
            default:
                // All products
                break;
        }

        $products = $query->paginate(12);

        return view('products.index', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('products.index');
        }

        $products = Product::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->paginate(12);

        return view('products.search', compact('products', 'query'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['images', 'variants', 'reviews.user'])->firstOrFail();

        // Increment view count
        $product->increment('views');

        return view('products.show', compact('product'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->with(['images', 'category', 'variants'])
            ->active()
            ->paginate(12);

        return view('products.index', compact('products', 'category'));
    }

    public function eco()
    {
        $products = Product::where('is_eco', true)->with('images', 'variants')->paginate(12);
        return view('products.eco', compact('products'));
    }

    public function virtualPreview($productId)
    {
        $product = Product::findOrFail($productId);
        return view('products.virtual-preview', compact('product'));
    }

    public function storeVirtualPreview(Request $request, $productId)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::findOrFail($productId);
        $path = $request->file('photo')->store('virtual-previews', 'public');

        // Simple overlay logic (placeholder for actual image processing)
        $previewPath = 'previews/' . uniqid() . '.jpg';
        // Here you would use image processing library to overlay fabric on photo

        VirtualPreview::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'photo_path' => $path,
            'preview_image' => $previewPath,
        ]);

        return redirect()->back()->with('success', 'Virtual preview created!');
    }

    public function virtualPreviewIndex()
    {
        $products = Product::with('images')->active()->get();
        return view('virtual-preview.index', compact('products'));
    }
}
