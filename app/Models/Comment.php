<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'blog_id',
        'user_id',
        'content',
    ];

    // user who wrote this comment
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // the blog that this comment belong to
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
