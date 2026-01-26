<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;
    public $chat;
    public $recipientId;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $chat, $recipientId)
    {
        $this->message = $message;
        $this->chat = $chat;
        $this->recipientId = $recipientId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->recipientId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'NewChatMessage';
    }

    /**
     * Data payload for broadcast.
     */
    public function broadcastWith(): array
    {
        $senderName = 'User';
        if (isset($this->message->sender_name)) {
            $senderName = $this->message->sender_name;
        } elseif (isset($this->message->sender) && is_object($this->message->sender)) {
            $senderName = $this->message->sender->name ?? 'User';
        }

        return [
            'chat_id' => $this->chat->id,
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'sender_name' => $senderName,
                'message' => $this->message->message,
                'created_at' => is_object($this->message->created_at) 
                    ? $this->message->created_at->toIso8601String() 
                    : now()->toIso8601String(),
            ],
            'notification' => [
                'type' => 'new_chat_message',
                'title' => 'Pesan Baru',
                'body' => $senderName . ': ' . \Str::limit($this->message->message, 50),
                'chat_id' => $this->chat->id,
            ],
        ];
    }
}
