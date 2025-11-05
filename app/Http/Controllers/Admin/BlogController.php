<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs
     */
    public function index()
    {
        $blogs = Blog::with('author')->orderBy('order')->orderBy('created_at', 'desc')->get();

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created blog
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blogs'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle tags (comma-separated string to array)
        if (isset($validated['tags']) && is_string($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        // Set author as current user
        $validated['author_id'] = auth()->id();

        $validated['is_published'] = $request->boolean('is_published');

        // Set published_at if publishing
        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $blog = Blog::create($validated);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully!');
    }

    /**
     * Show the form for editing a blog
     */
    public function edit(Blog $blog)
    {
        // Ensure blog has all relationships loaded
        $blog->load('author');

        // Convert tags array to comma-separated string for form display
        // Don't modify the model directly to avoid issues with array casting
        $formattedTags = '';
        if ($blog->tags && is_array($blog->tags)) {
            $formattedTags = implode(', ', $blog->tags);
        }

        return view('admin.blogs.edit', compact('blog', 'formattedTags'));
    }

    /**
     * Update the specified blog
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blogs,slug,'.$blog->id],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle tags (comma-separated string to array)
        if (isset($validated['tags'])) {
            if (empty(trim($validated['tags']))) {
                $validated['tags'] = [];
            } elseif (is_string($validated['tags'])) {
                $tags = array_map('trim', explode(',', $validated['tags']));
                $validated['tags'] = array_values(array_filter($tags)); // Remove empty values and reindex
            }
        } else {
            $validated['tags'] = [];
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published');

        // Set published_at if publishing
        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified blog
     */
    public function destroy(Blog $blog)
    {
        // Delete featured image if exists
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully!');
    }
}
