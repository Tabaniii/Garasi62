<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundRequest extends Model
{
    protected $fillable = [
        'user_id',
        'program_name',
        'program_type',
        'description',
        'amount_requested',
        'amount_approved',
        'bank_name',
        'account_number',
        'account_holder',
        'status',
        'submitted_at',
        'archived_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(FundRequestDocument::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function signature()
    {
        return $this->hasOne(DigitalSignature::class);
    }
}
