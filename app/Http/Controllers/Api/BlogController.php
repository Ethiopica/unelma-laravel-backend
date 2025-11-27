<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BlogController extends Controller
{
    /**
     * Get all published blog posts
     */
    public function index(Request $request)
    {
        try {
            $query = Blog::where('is_published', true)
                ->with($this->blogRelations())
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc');

            // Filter by category if provided
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Search by title or content
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $blogs = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $blogs->items(),
                'meta' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'per_page' => $blogs->perPage(),
                    'total' => $blogs->total(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Blogs API Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch blogs',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    /**
     * Get a single blog post by id
     */

    public function show($id)
    {
        $blog = Blog::with($this->blogRelations())->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }

    public function showBySlug($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->with($this->blogRelations())
            ->firstOrFail();

        // Increment views
        $blog->incrementViews();

        return response()->json([
            'success' => true,
            'data' => $blog,
        ]);
    }
    /**
     * Get blog categories
     */
    public function categories()
    {
        $categories = Blog::where('is_published', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get recent blog posts
     */
    public function recent(Request $request)
    {
        $limit = $request->get('limit', 5);

        $blogs = Blog::where('is_published', true)
            ->with($this->blogRelations())
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $blogs,
        ]);
    }

    /**
     * Get the single latest published blog post.
     */
    public function latest()
    {
        $blog = Blog::where('is_published', true)
            ->with($this->blogRelations())
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->first();

        if (! $blog) {
            return response()->json([
                'success' => false,
                'message' => 'No published blogs available.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog,
        ]);
    }

    /**
     * Get popular blog posts (by views)
     */
    public function popular(Request $request)
    {
        $limit = $request->get('limit', 5);

        $blogs = Blog::where('is_published', true)
            ->with($this->blogRelations())
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $blogs,
        ]);
    }

    /**
     * Determine which relations can be safely eager loaded.
     */
    protected function blogRelations(): array
    {
        $relations = [
            'author:id,name,email,profile_picture',
        ];

        if (Schema::hasTable('blog_comments')) {
            $relations[] = 'comments';

            if (Schema::hasColumn('blog_comments', 'user_id')) {
                $relations[] = 'comments.user:id,name,profile_picture';
            }
        }

        return $relations;
    }
}
