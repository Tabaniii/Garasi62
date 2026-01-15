<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CarApproval;

class car extends Model
{
    protected $table = 'car';

    protected $fillable = [
        'image',
        'tipe',
        'tahun',
        'brand',
        'nama',
        'kilometer',
        'transmisi',
        'harga',
        'metode',
        'kapasitasmesin',
        'stock',
        'vin',
        'msrp',
        'dealer_discounts',
        'description',
        'interior_features',
        'safety_features',
        'extra_features',
        'technical_specs',
        'location',
        'seller_id',
        'status',
    ];

    protected $casts = [
        'image' => 'array',
        'interior_features' => 'array',
        'safety_features' => 'array',
        'extra_features' => 'array',
    ];

    /**
     * Relationship dengan User (seller)
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Relationship dengan CarApproval
     */
    public function approvals()
    {
        return $this->hasMany(CarApproval::class);
    }

    /**
     * Relationship dengan Wishlist
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Relationship dengan Report
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Scope untuk mobil yang sudah disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk mobil yang pending approval
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk mobil yang ditolak
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if car is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if car is pending approval
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if car is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}