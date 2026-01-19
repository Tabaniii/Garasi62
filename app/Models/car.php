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
     * Accessor untuk image - normalisasi path separator
     * Karena image sudah di-cast sebagai array, value yang diterima sudah berupa array
     */
    public function getImageAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // Jika masih string (belum di-cast), decode dulu
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        
        if (!is_array($value)) {
            return null;
        }
        
        // Normalisasi path separator (backslash ke forward slash untuk URL web)
        return array_map(function($path) {
            if (is_string($path)) {
                return str_replace('\\', '/', $path);
            }
            return $path;
        }, $value);
    }

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
     * Relationship dengan Cart
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
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