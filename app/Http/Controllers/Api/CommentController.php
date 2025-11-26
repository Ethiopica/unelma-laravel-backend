<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Store a new comment
    public function store(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        try {
            $comment = Comment::create([
                'blog_id' => $blog->id,
                'user_id' => auth()->id(),
                'content' => $validated['content'],
            ]);
    
            // Load user info for frontend
            $comment->load('user:id,name,profile_picture');
    
            return response()->json([
                'success' => true,
                'data' => $blog
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
