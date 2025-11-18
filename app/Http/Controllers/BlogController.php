<?php
namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()->with('author');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(12);

        $categories = BlogPost::published()
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(BlogPost $blogPost)
    {
        if (!$blogPost->is_published) {
            abort(404);
        }

        // Increment views
        $blogPost->increment('views');

        $blogPost->load('author');

        // Related posts
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $blogPost->id)
            ->where('category', $blogPost->category)
            ->limit(3)
            ->get();

        return view('blog.index', compact('posts', 'categories'));

    }
    // In BlogController.php
public function search(Request $request)
{
    // Your search logic here
}

}