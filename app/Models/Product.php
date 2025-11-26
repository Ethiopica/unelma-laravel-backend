<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'is_featured',
        'is_active',
        'order',
        'favorite_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
        'favorite_count' => 'integer',
    ];

    protected $appends = ['image_url'];

    /**
     * Get the absolute URL for the product image.
     */
    public function getImageUrlAttribute()
    {
        try {
            if (! $this->image) {
                return null;
            }

            return Storage::url($this->image);
        } catch (\Exception $e) {
            \Log::warning('Failed to generate image URL for product: '.$e->getMessage());

            return $this->image ? asset('storage/'.$this->image) : null;
        }
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'item_id')
            ->where('favorite_type', Favorite::TYPE_PRODUCT);
    }
}
