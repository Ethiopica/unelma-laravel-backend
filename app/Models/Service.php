<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'image',
        'image_url',
        'is_active',
        'is_featured',
        'order',
        'stripe_price_id',
        'payment_type', // 'subscription', 'one_time', or null (auto-detect from Stripe)
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'favorite_count' => 'integer',
    ];

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    protected $appends = ['image_local_url', 'full_image_url'];

    /**
     * Get the URL for the service image (relative path for local, full URL for S3).
     */
    public function getImageLocalUrlAttribute()
    {
        try {
            if (! $this->image) {
                return null;
            }

            // Use Storage::url() - works correctly with Supabase when AWS_URL is configured
            $disk = config('filesystems.default');
            
            // For cloud storage (S3, Supabase), use the configured disk
            if (in_array($disk, ['s3', 'supabase', 'cloudinary'])) {
                return Storage::disk($disk)->url($this->image);
            }

            return Storage::url($this->image);
        } catch (\Exception $e) {
            \Log::warning('Failed to generate image URL for service: ' . $e->getMessage());

            return $this->image ? asset('storage/' . $this->image) : null;
        }
    }

    /**
     * Get the full absolute URL for the service image.
     */
    public function getFullImageUrlAttribute()
    {
        // Priority: external image_url > uploaded image > null
        if ($this->image_url && (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://'))) {
            return $this->image_url;
        }

        if (! $this->image) {
            return $this->image_url;
        }

        try {
            $disk = config('filesystems.default');
            
            // For cloud storage (S3, Supabase), Storage::url() returns full URL when AWS_URL is configured
            if (in_array($disk, ['s3', 'supabase', 'cloudinary'])) {
                return Storage::disk($disk)->url($this->image);
            }

            // For local storage, prepend the APP_URL
            $appUrl = rtrim(config('app.url'), '/');
            $storagePath = Storage::url($this->image);
            
            return $appUrl . $storagePath;
        } catch (\Exception $e) {
            \Log::warning('Failed to generate full image URL for service: '.$e->getMessage());
            
            $appUrl = rtrim(config('app.url'), '/');
            return $this->image ? $appUrl . '/storage/' . ltrim($this->image, '/') : null;
        }
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'item_id')
            ->where('favorite_type', Favorite::TYPE_SERVICE);
    }

    /**
     * Safely get plans if the plans table exists
     */
    public function getPlansSafely()
    {
        if (!Schema::hasTable('plans')) {
            return collect([]);
        }

        try {
            return $this->plans;
        } catch (\Exception $e) {
            \Log::warning('Failed to load plans for service: ' . $e->getMessage());
            return collect([]);
        }
    }
}
