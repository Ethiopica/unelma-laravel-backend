<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'favorite_type',
        'item_id',
    ];

    protected $casts = [
        'item_id' => 'integer',
    ];

    public const TYPE_BLOG = 'blog';
    public const TYPE_SERVICE = 'service';
    public const TYPE_PRODUCT = 'product';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForContent(Builder $query, string $type, int $id): Builder
    {
        return $query->where('favorite_type', $type)
            ->where('item_id', $id);
    }
}


