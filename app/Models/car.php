<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'image' => 'array',
        'interior_features' => 'array',
        'safety_features' => 'array',
        'extra_features' => 'array',
    ];
}