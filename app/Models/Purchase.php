<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'stripe_session_id',
        'stripe_price_id',
        'amount',
        'currency',
        'status',
        'product_id',
        'service_id',
        'plan_id',
        'quantity',
        'metadata',
        'purchased_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'integer',
        'metadata' => 'array',
        'purchased_at' => 'datetime',
    ];

    /**
     * Get the user who made this purchase
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product if this purchase was for a product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the service if this purchase was for a service
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the plan if this purchase was for a plan
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
