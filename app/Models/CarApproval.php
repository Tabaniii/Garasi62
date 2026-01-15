<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarApproval extends Model
{
    protected $fillable = [
        'car_id',
        'admin_id',
        'action',
        'notes',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship dengan Car
     */
    public function car()
    {
        return $this->belongsTo(car::class);
    }

    /**
     * Relationship dengan User (admin)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope untuk approval actions
     */
    public function scopeApproved($query)
    {
        return $query->where('action', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('action', 'rejected');
    }
}
