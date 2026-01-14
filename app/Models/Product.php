<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name','category','sku','highlights','rating','rating_count','image_url',
        'description',
        'price',
        'stripe_price_id',
        'payment_type', // 'subscription', 'one_time', or null (auto-detect from Stripe)
        'image',
        'is_featured',
        'is_active',
        'order',
        'favorite_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'rating_count' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
        'favorite_count' => 'integer',
    ];

    protected $appends = ['image_local_url', 'full_image_url'];

    /**
     * Get the URL for the product image (relative path for local, full URL for S3/Supabase).
     */
    public function getImageLocalUrlAttribute()
    {
        try {
            if (! $this->image) {
                return null;
            }

            // Check which disk is being used
            $disk = config('filesystems.default');
            
            // For S3, Supabase, or cloud storage, return full URL
            if (in_array($disk, ['s3', 'supabase', 'cloudinary'])) {
                // Check if using Supabase (by checking if AWS_URL contains supabase.co)
                $awsUrl = config('filesystems.disks.s3.url');
                if ($awsUrl && str_contains($awsUrl, 'supabase.co')) {
                    // Generate Supabase URL format: https://[PROJECT].supabase.co/storage/v1/object/public/[BUCKET]/[PATH]
                    $baseUrl = rtrim($awsUrl, '/');
                    $imagePath = ltrim($this->image, '/');
                    return "{$baseUrl}/{$imagePath}";
                }
                
                return Storage::disk($disk)->url($this->image);
            }

            // For local storage, Storage::url() returns a path like /storage/products/...
            // This is a relative path that works with the storage symlink
            return Storage::url($this->image);
        } catch (\Exception $e) {
            \Log::warning('Failed to generate image URL for product: '.$e->getMessage());

            // Fallback: construct the path manually
            return $this->image ? '/storage/'.ltrim($this->image, '/') : null;
        }
    }

    /**
     * Get the full absolute URL for the product image.
     * This is useful for API responses where the frontend needs a complete URL.
     */
    public function getFullImageUrlAttribute()
    {
        // Priority: external image_url > uploaded image > null
        if ($this->image_url && (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://'))) {
            return $this->image_url;
        }

        if (! $this->image) {
            return $this->image_url; // Return image_url even if it's a relative path
        }

        try {
            $disk = config('filesystems.default');
            
            // For S3, Supabase, or cloud storage, return the full URL directly
            if (in_array($disk, ['s3', 'supabase', 'cloudinary'])) {
                // Check if using Supabase (by checking if AWS_URL contains supabase.co)
                $awsUrl = config('filesystems.disks.s3.url');
                if ($awsUrl && str_contains($awsUrl, 'supabase.co')) {
                    // Generate Supabase URL format: https://[PROJECT].supabase.co/storage/v1/object/public/[BUCKET]/[PATH]
                    $baseUrl = rtrim($awsUrl, '/');
                    $imagePath = ltrim($this->image, '/');
                    return "{$baseUrl}/{$imagePath}";
                }
                
                return Storage::disk($disk)->url($this->image);
            }

            // For local storage, prepend the APP_URL
            $appUrl = rtrim(config('app.url'), '/');
            $storagePath = Storage::url($this->image);
            
            return $appUrl . $storagePath;
        } catch (\Exception $e) {
            \Log::warning('Failed to generate full image URL for product: '.$e->getMessage());
            
            // Fallback
            $appUrl = rtrim(config('app.url'), '/');
            return $this->image ? $appUrl . '/storage/' . ltrim($this->image, '/') : null;
        }
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'item_id')
            ->where('favorite_type', Favorite::TYPE_PRODUCT);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(ProductRating::class);
    }

    /**
     * Update average rating and count
     */
    public function updateAverageRating(): void
    {
        // Get fresh count and average from database
        $stats = ProductRating::where('product_id', $this->id)
            ->selectRaw('COUNT(*) as count, AVG(rating) as average')
            ->first();
        
        $count = $stats->count ?? 0;
        $average = $count > 0 ? round($stats->average, 2) : 0;
        
        $this->update([
            'rating' => $average,
            'rating_count' => $count,
        ]);

        \Log::info('Product rating updated', [
            'product_id' => $this->id,
            'rating' => $average,
            'rating_count' => $count,
        ]);
    }
}
