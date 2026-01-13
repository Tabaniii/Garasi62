<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    // Public method - menampilkan semua blog
    public function index(Request $request)
    {
        $query = Blog::published()->orderBy('published_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $blogs = $query->paginate(6);
        
        // Get categories and featured blogs for sidebar
        $categories = Blog::published()->whereNotNull('category')
            ->distinct()->pluck('category')->sort()->values();
        
        $featuredBlogs = Blog::published()->orderBy('published_at', 'desc')->limit(3)->get();

        return view('blog-list', compact('blogs', 'categories', 'featuredBlogs'));
    }

    // Public method - menampilkan detail blog
    public function show($slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();
        
        // Load comments with nested replies recursively (only approved)
        // Using recursive eager loading for unlimited depth
        $blog->load(['comments' => function($query) {
            $query->where('status', 'approved')
                  ->whereNull('parent_id')
                  ->orderBy('created_at', 'desc');
        }]);
        
        // Load nested replies recursively for each comment
        $blog->comments->each(function($comment) {
            $this->loadNestedReplies($comment);
        });
        
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where(function($query) use ($blog) {
                if($blog->category) {
                    $query->where('category', $blog->category);
                } else {
                    $query->whereNotNull('id'); // Jika tidak ada category, ambil semua
                }
            })
            ->limit(3)
            ->get();
        
        return view('blog', compact('blog', 'relatedBlogs'));
    }

    // Helper method to load nested replies recursively
    private function loadNestedReplies($comment)
    {
        $comment->load(['replies' => function($query) {
            $query->where('status', 'approved')->orderBy('created_at', 'asc');
        }]);
        
        foreach ($comment->replies as $reply) {
            $this->loadNestedReplies($reply);
        }
    }

    // Admin methods - CRUD
    public function adminIndex()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return view('blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except(['image', 'published_at']); // <-- abaikan published_at dari input

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $data['image'] = $imagePath;
        }

        // Generate slug
        $data['slug'] = Str::slug($data['title']);

        // Handle tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tags);
        }

        // Jika status published, langsung set published_at = sekarang
        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        } else {
            $data['published_at'] = null; // pastikan draft tidak punya published_at
        }

        // Set user_id
        $data['user_id'] = auth()->id();

        Blog::create($data);

        return redirect()->route('blogs.admin.index')->with('success', 'Blog berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date',
        ]);

        $blog = Blog::findOrFail($id);
        $data = $request->except(['image', 'published_at']); // <-- abaikan published_at dari input

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $imagePath = $request->file('image')->store('blogs', 'public');
            $data['image'] = $imagePath;
        }

        // Update slug jika title berubah
        if ($blog->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Handle tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tags);
        } else {
            $data['tags'] = null;
        }

        // Atur published_at berdasarkan status — selalu sekarang jika published
        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        } else {
            $data['published_at'] = null;
        }

        $blog->update($data);

        return redirect()->route('blogs.admin.index')->with('success', 'Blog berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();

        return redirect()->route('blogs.admin.index')->with('success', 'Blog berhasil dihapus!');
    }
}