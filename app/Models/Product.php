<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = ['image_url'];

    /**
     * Get the absolute URL for the product image.
     */
    public function getImageUrlAttribute()
    {
        try {
            if (!$this->image) {
                return null;
            }
            return Storage::url($this->image);
        } catch (\Exception $e) {
            \Log::warning('Failed to generate image URL for product: ' . $e->getMessage());
            return $this->image ? asset('storage/' . $this->image) : null;
        }
    }
}
