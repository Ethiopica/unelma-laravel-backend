<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable=[
        'name','price','period','stripe_price_id','features'];

    protected $casts=[
        'features' =>'array',
    ];

    // plans are part of service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
}
