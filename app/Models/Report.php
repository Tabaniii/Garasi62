<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'car_id',
        'reporter_id',
        'seller_id',
        'reason',
        'message',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Relationship dengan Car
     */
    public function car()
    {
        return $this->belongsTo(car::class);
    }

    /**
     * Relationship dengan User (reporter)
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Relationship dengan User (seller)
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Relationship dengan User (admin yang review)
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get reason label in Indonesian
     */
    public function getReasonLabelAttribute()
    {
        $reasons = [
            'false_information' => 'Informasi Palsu',
            'inappropriate_content' => 'Konten Tidak Pantas',
            'spam' => 'Spam',
            'duplicate' => 'Duplikat',
            'scam' => 'Penipuan',
            'other' => 'Lainnya',
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sedang Ditinjau',
            'resolved' => 'Selesai',
            'dismissed' => 'Ditolak',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Scope untuk reports pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk reports by seller
     */
    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }
}
