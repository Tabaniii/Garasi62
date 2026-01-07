<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalSignature extends Model
{
    protected $fillable = [
        'fund_request_id',
        'signed_by',
        'signature_path',
        'signed_at'
    ];

    public function fundRequest()
    {
        return $this->belongsTo(FundRequest::class);
    }

    public function signer()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
