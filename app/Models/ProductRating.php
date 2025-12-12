<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'feedback',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get the product that was rated
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who rated (includes name and profile_picture)
     */
    public function user()
    {
        return $this->belongsTo(User::class)->select(['id', 'name', 'profile_picture']);
    }
}
