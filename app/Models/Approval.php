<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'fund_request_id',
        'approver_id',
        'level',
        'decision',
        'notes',
        'decided_at'
    ];

    public function fundRequest()
    {
        return $this->belongsTo(FundRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
