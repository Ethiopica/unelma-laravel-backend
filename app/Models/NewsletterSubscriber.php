<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'status',
        'unelma_uid',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    /**
     * Scope for pending subscribers that need to be synced.
     */
    public function scopePendingSync($query)
    {
        return $query->whereIn('status', ['pending', 'subscribed'])
                     ->whereNull('synced_at');
    }

    /**
     * Mark as synced with Unelma Mail.
     */
    public function markAsSynced(?string $unelmaUid = null): void
    {
        $this->update([
            'status' => 'synced',
            'unelma_uid' => $unelmaUid,
            'synced_at' => now(),
        ]);
    }
}








































