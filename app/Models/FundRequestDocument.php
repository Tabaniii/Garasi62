<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundRequestDocument extends Model
{
    protected $fillable = [
        'fund_request_id',
        'document_type',
        'file_path',
        'original_name',
        'file_size'
    ];

    public function fundRequest()
    {
        return $this->belongsTo(FundRequest::class);
    }
}
