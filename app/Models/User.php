<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'is_admin',
        'profile_picture',
    ];
    // all comments written by this user
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }


    /**
     * Get all product ratings by this user
     */
    public function productRatings(): HasMany
    {
        return $this->hasMany(ProductRating::class);
    }

    /**
     * Get all one-time purchases by this user
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Check if user has purchased a specific product.
     * 
     * Note: This checks payment subscriptions (Stripe), not newsletter/email subscriptions (Unelma Mail).
     * A product is considered purchased if the user has an active payment subscription
     * with the product's stripe_price_id.
     */
    public function hasPurchasedProduct(Product $product): bool
    {
        // If product doesn't have a stripe_price_id, it's free or not purchasable
        if (!$product->stripe_price_id) {
            return false;
        }
         // Check **one-time purchases**
        $oneTime = $this->purchases()
        ->where('stripe_price_id', $product->stripe_price_id)
        ->where('status', 'completed')
        ->exists();

        // Check **subscriptions** if needed
        // Check if user has any payment subscription (active or completed) with this product's price
        // Note: subscriptions() is Laravel Cashier's relationship for payment subscriptions
        $subscription = $this->subscriptions()
            ->where('stripe_price', $product->stripe_price_id)
            ->whereIn('stripe_status', ['active', 'trialing', 'past_due', 'canceled', 'complete'])
            ->exists();

        return $oneTime || $subscription;
        
       
    }


    public function hasFavorited(string $type, int $itemId): bool
    {
        return $this->favorites()
            ->where('favorite_type', $type)
            ->where('item_id', $itemId)
            ->exists();
    }

    /**
     * Get the full URL for the user's profile picture.
     * Returns null if no profile picture exists.
     * Handles both Google OAuth avatars (full URLs) and uploaded images (relative paths).
     */
    public function getProfilePictureUrlAttribute()
    {
        if (!$this->profile_picture) {
            return null;
        }

        // If it's already a full URL (Google OAuth avatar), return as is
        if (str_starts_with($this->profile_picture, 'http://') || str_starts_with($this->profile_picture, 'https://')) {
            return $this->profile_picture;
        }

        // For uploaded images, convert relative path to full URL
        try {
            return asset('storage/' . $this->profile_picture);
        } catch (\Exception $e) {
            \Log::warning('Failed to generate profile picture URL for user: ' . $e->getMessage());
            return $this->profile_picture ? asset('storage/' . $this->profile_picture) : null;
        }
    }
}
