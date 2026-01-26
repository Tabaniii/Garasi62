<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'is_read',
        'reply_to',
        'edited_at',
        'deleted_at',
        'is_deleted_for_sender',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_deleted_for_sender' => 'boolean',
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime',
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

    /**
     * Relationship dengan Message yang di-reply
     */
    public function repliedTo()
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }

    /**
     * Relationship dengan Messages yang reply ke message ini
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to');
    }

    /**
     * Check if message is deleted
     */
    public function isDeleted()
    {
        return $this->deleted_at !== null;
    }

    /**
     * Check if message is edited
     */
    public function isEdited()
    {
        return $this->edited_at !== null;
    }
}

