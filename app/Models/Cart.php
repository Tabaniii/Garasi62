<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'buyer_id',
        'car_id',
        'quantity',
    ];

    /**
     * Relationship dengan User (buyer)
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Relationship dengan Car
     */
    public function car()
    {
        return $this->belongsTo(car::class, 'car_id');
    }
}
