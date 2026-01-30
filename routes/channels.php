<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // Parse chat ID to get buyer_id and seller_id
    if (!str_starts_with($chatId, 'chat_')) {
        return false;
    }
    
    $parts = explode('_', $chatId);
    if (count($parts) < 3) {
        return false;
    }
    
    $buyerId = (int)$parts[1];
    $sellerId = (int)$parts[2];
    
    // Verify user is part of this chat
    return in_array($user->id, [$buyerId, $sellerId]);
});

Broadcast::channel('presence-chat.{chatId}', function ($user, $chatId) {
    if (!str_starts_with($chatId, 'chat_')) {
        return false;
    }
    
    $parts = explode('_', $chatId);
    if (count($parts) < 3) {
        return false;
    }
    
    $buyerId = (int)$parts[1];
    $sellerId = (int)$parts[2];
    
    if (!in_array($user->id, [$buyerId, $sellerId])) {
        return false;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
