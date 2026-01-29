<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TypingIndicator implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $chatId;
    public $userId;
    public $typing;

    public function __construct($chatId, $userId, $typing)
    {
        $this->chatId = $chatId;
        $this->userId = $userId;
        $this->typing = $typing;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->chatId)];
    }

    public function broadcastAs(): string
    {
        return 'TypingIndicator';
    }

    public function broadcastWith(): array
    {
        return [
            'chat_id' => $this->chatId,
            'user_id' => $this->userId,
            'typing' => $this->typing,
        ];
    }
}
