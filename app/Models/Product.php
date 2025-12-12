<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'sku',
        'highlights',
        'rating',
        'rating_count',
        'image_url',
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

    protected $appends = ['image_local_url'];

    /**
     * Get the URL for the product image (relative path).
     */
    public function getImageLocalUrlAttribute()
    {
        try {
            if (! $this->image) {
                return null;
            }

            // Storage::url() returns a path like /storage/products/...
            // This is a relative path that works with the storage symlink
            return Storage::url($this->image);
        } catch (\Exception $e) {
            Log::warning('Failed to generate image URL for product: ' . $e->getMessage());

            // Fallback: construct the path manually
            return $this->image ? '/storage/' . ltrim($this->image, '/') : null;
        }
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'item_id')
            ->where('favorite_type', Favorite::TYPE_PRODUCT);
    }

    /**
     * Get all ratings for this product
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(ProductRating::class);
    }

    /**
     * Recalculate and update the average rating and count
     */
    public function updateAverageRating(): void
    {
        $ratings = $this->ratings();

        $this->update([
            'rating' => round($ratings->avg('rating') ?? 0, 2),
            'rating_count' => $ratings->count(),
        ]);
    }
}
