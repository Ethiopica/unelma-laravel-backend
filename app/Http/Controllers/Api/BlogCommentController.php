<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    /**
     * List approved comments for the given blog.
     */
    public function index(Blog $blog): JsonResponse
    {
        $comments = $blog->comments()
            ->where('is_approved', true)
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
        ]);
    }

    /**
     * Store a new comment for the given blog.
     */
    public function store(Request $request, Blog $blog): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'comment' => ['required', 'string'],
        ]);

        /** @var BlogComment $comment */
        $comment = $blog->comments()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thanks for sharing your thoughts!',
            'data' => $comment,
        ], 201);
    }
}

















