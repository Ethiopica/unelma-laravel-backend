<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    // List all comments for a blog

    public function showByBlog(Blog $blog)
    {
        $comments = $blog->comments()->with('user')->get();
        return view('admin.blogs.comments', compact('blog', 'comments'));
    }


    // Delete a comment
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Comment deleted successfully.');
    }
}
