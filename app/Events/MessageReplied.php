<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReplied implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;
    public $chat;
    public $repliedToMessage;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $chat, $repliedToMessage)
    {
        $this->message = $message;
        $this->chat = $chat;
        $this->repliedToMessage = $repliedToMessage;
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
        return 'MessageReplied';
    }

    /**
     * Data payload for broadcast.
     */
    public function broadcastWith(): array
    {
        $senderName = 'User';
        if (isset($this->message->sender) && is_object($this->message->sender)) {
            $senderName = $this->message->sender->name ?? 'User';
        } elseif (isset($this->message->sender_name)) {
            $senderName = $this->message->sender_name;
        }

        $repliedToSenderName = 'User';
        if (isset($this->repliedToMessage->sender_name)) {
            $repliedToSenderName = $this->repliedToMessage->sender_name;
        } elseif (isset($this->repliedToMessage->sender) && is_object($this->repliedToMessage->sender)) {
            $repliedToSenderName = $this->repliedToMessage->sender->name ?? 'User';
        }

        return [
            'chat_id' => $this->chat->id,
            'message' => [
                'id' => $this->message->id,
                'chat_id' => $this->message->chat_id,
                'sender_id' => $this->message->sender_id,
                'sender_name' => $senderName,
                'message' => $this->message->message,
                'reply_to' => $this->message->reply_to ?? null,
                'replied_to_message' => [
                    'id' => $this->repliedToMessage->id ?? null,
                    'sender_name' => $repliedToSenderName,
                    'message' => $this->repliedToMessage->message ?? '',
                ],
                'created_at' => is_object($this->message->created_at) 
                    ? $this->message->created_at->toIso8601String() 
                    : now()->toIso8601String(),
            ],
            'notification' => [
                'type' => 'message_replied',
                'title' => 'Pesan Dibalas',
                'body' => $senderName . ' membalas pesan Anda',
                'chat_id' => $this->chat->id,
            ],
        ];
    }
}
