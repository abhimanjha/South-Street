<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\User;
use App\Notifications\NewProductNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'fabric' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'care_instructions' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_trending' => 'boolean',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|max:2048',
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string',
            'variants.*.color' => 'nullable|string',
            'variants.*.color_code' => 'nullable|string',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price_adjustment' => 'nullable|numeric'
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        $validated['slug'] = $slug;
        $validated['sku'] = 'SKU-' . strtoupper(Str::random(8));

        $product = Product::create($validated);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index
                ]);
            }
        }

        // Handle variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variant['size'] ?? null,
                    'color' => $variant['color'] ?? null,
                    'color_code' => $variant['color_code'] ?? null,
                    'stock' => $variant['stock'],
                    'price_adjustment' => $variant['price_adjustment'] ?? 0,
                    'sku' => 'VAR-' . strtoupper(Str::random(8))
                ]);
            }
        }

        // Send notification to all users about new product
        $users = User::all();
        Notification::send($users, new NewProductNotification($product));

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load(['images', 'variants']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'fabric' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'care_instructions' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_trending' => 'boolean',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|max:2048'
        ]);

        // Generate unique slug if name changed
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $count = 1;

        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        $validated['slug'] = $slug;

        $product->update($validated);

        // Handle new images
        if ($request->hasFile('images')) {
            $currentCount = $product->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $currentCount === 0 && $index === 0,
                    'order' => $currentCount + $index
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'action' => 'nullable|in:add,set,subtract'
        ]);

        if ($request->action === 'add') {
            $product->increment('stock_quantity', $validated['stock_quantity']);
            $message = "Added {$validated['stock_quantity']} units to stock";
        } elseif ($request->action === 'subtract') {
            $product->decrement('stock_quantity', $validated['stock_quantity']);
            $message = "Subtracted {$validated['stock_quantity']} units from stock";
        } else {
            $product->update(['stock_quantity' => $validated['stock_quantity']]);
            $message = "Stock quantity set to {$validated['stock_quantity']}";
        }

        return back()->with('success', $message);
    }

    public function markOutOfStock(Product $product)
    {
        $product->update(['stock_quantity' => 0, 'is_active' => false]);
        return back()->with('success', 'Product marked as out of stock');
    }

    public function markInStock(Product $product)
    {
        if ($product->stock_quantity == 0) {
            $product->update(['stock_quantity' => 1]);
        }
        $product->update(['is_active' => true]);
        return back()->with('success', 'Product marked as in stock');
    }

    public function destroy(Product $product)
    {
        // Delete images
        foreach ($product->images as $image) {
            Storage::delete($image->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}
