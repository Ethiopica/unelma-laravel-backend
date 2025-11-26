<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'image',
        'is_active',
        'is_featured',
        'order',
        'favorite_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'favorite_count' => 'integer',
    ];

    protected $appends = ['image_url'];

    /**
     * Get the absolute URL for the service image.
     */
    public function getImageUrlAttribute()
    {
        try {
            if (! $this->image) {
                return null;
            }

            return Storage::url($this->image);
        } catch (\Exception $e) {
            \Log::warning('Failed to generate image URL for service: '.$e->getMessage());

            return $this->image ? asset('storage/'.$this->image) : null;
        }
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'item_id')
            ->where('favorite_type', Favorite::TYPE_SERVICE);
    }
}
