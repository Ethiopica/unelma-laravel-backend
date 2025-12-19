<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'author_id',
        'category',
        'tags',
        'is_published',
        'published_at',
        'views',
        'favorite_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'order',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
        'favorite_count' => 'integer',
        'order' => 'integer',
    ];

    protected $appends = [
        'featured_image_url',
    ];

    /**
     * Generate slug from title
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            if ($blog->is_published && ! $blog->published_at) {
                $blog->published_at = now();
            }
        });

        static::updating(function ($blog) {
            if ($blog->is_published && ! $blog->published_at) {
                $blog->published_at = now();
            }
        });
    }

    /**
     * Get the author that owns the blog
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // All comments for this blog
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Get the route key name
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Absolute URL for the featured image (for frontend consumption)
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        try {
            if (! $this->featured_image) {
                return null;
            }

            return asset('storage/' . $this->featured_image);
        } catch (\Exception $e) {
            \Log::warning('Failed to generate featured image URL for blog: ' . $e->getMessage());

            return null;
        }
    }
}
