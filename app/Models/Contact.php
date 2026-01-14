<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'ip_address',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Cek apakah email sudah mengirim pesan hari ini
     */
    public static function hasSentToday(string $email): bool
    {
        return self::where('email', $email)
            ->whereDate('sent_at', Carbon::today())
            ->exists();
    }
}
