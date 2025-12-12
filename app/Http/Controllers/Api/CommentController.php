<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Get all comments for a blog
    public function index($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            
            $comments = $blog->comments()
                ->with('user:id,name,profile_picture')
                ->latest()
                ->get();
            
            // Transform comments to include full profile picture URLs
            $transformedComments = $comments->map(function ($comment) {
                $commentArray = $comment->toArray();
                if ($comment->user) {
                    $commentArray['user']['profile_picture'] = $comment->user->profile_picture_url;
                }
                return $commentArray;
            });
            
            return response()->json([
                'success' => true,
                'data' => $transformedComments->values()->all(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

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
    
            // Reload blog with comments to include the new comment
            $blog->load('comments.user:id,name,profile_picture');
    
            // Transform blog to include full profile picture URLs
            $blogData = $blog->toArray();
            if (isset($blogData['comments'])) {
                foreach ($blogData['comments'] as $key => $commentData) {
                    if (isset($commentData['user']) && isset($commentData['user']['profile_picture'])) {
                        $commentModel = $blog->comments->firstWhere('id', $commentData['id']);
                        if ($commentModel && $commentModel->user) {
                            $blogData['comments'][$key]['user']['profile_picture'] = $commentModel->user->profile_picture_url;
                        }
                    }
                }
            }
    
            return response()->json([
                'success' => true,
                'data' => $blogData
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
