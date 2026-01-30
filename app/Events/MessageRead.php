<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $chatId;
    public $readerId;
    public $messageIds;

    public function __construct($chatId, $readerId, $messageIds)
    {
        $this->chatId = $chatId;
        $this->readerId = $readerId;
        $this->messageIds = $messageIds;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->chatId)];
    }

    public function broadcastAs(): string
    {
        return 'MessageRead';
    }

    public function broadcastWith(): array
    {
        return [
            'chat_id' => $this->chatId,
            'reader_id' => $this->readerId,
            'message_ids' => $this->messageIds,
        ];
    }
}
