<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;
    public $chat;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $chat)
    {
        $this->message = $message;
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->chat->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    /**
     * Data payload for broadcast.
     */
    public function broadcastWith(): array
    {
        // Get sender name safely
        $senderName = 'User';
        if (isset($this->message->sender) && is_object($this->message->sender)) {
            $senderName = $this->message->sender->name ?? 'User';
        } elseif (isset($this->message->sender_name)) {
            $senderName = $this->message->sender_name;
        }
        
        return [
            'chat_id' => $this->chat->id,
            'message' => [
                'id' => $this->message->id,
                'chat_id' => $this->message->chat_id,
                'sender_id' => $this->message->sender_id,
                'sender_name' => $senderName,
                'message' => $this->message->message,
                'is_deleted' => $this->message->is_deleted ?? false,
                'is_read' => $this->message->is_read ?? false,
                'reply_to_message_id' => $this->message->reply_to_message_id ?? null,
                'reply_to_message' => $this->message->reply_to_message ?? null,
                'created_at' => is_object($this->message->created_at) 
                    ? $this->message->created_at->toIso8601String() 
                    : now()->toIso8601String(),
            ],
        ];
    }
}
