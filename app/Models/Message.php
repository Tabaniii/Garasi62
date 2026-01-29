<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'is_deleted',
        'is_read',
        'reply_to_message_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    /**
     * Relationship dengan Chat
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Relationship dengan User (sender)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_message_id');
    }
}
