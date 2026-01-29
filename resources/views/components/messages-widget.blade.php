@auth
@php
    $user = Auth::user();
    $role = $user->role;
    $currentUserId = $user->id;
    
    // Get chats from cache directly
    $cacheKey = 'user_chats_' . $user->id;
    $chatsData = \Illuminate\Support\Facades\Cache::get($cacheKey, []);
    
    $userChats = collect($chatsData)->map(function($chatCacheData, $chatId) use ($role, $currentUserId) {
        // Parse chat ID
        if (!str_starts_with($chatId, 'chat_')) {
            return null;
        }
        
        $parts = explode('_', $chatId);
        if (count($parts) < 3) {
            return null;
        }
        
        // NOTE: Chat ID is generated with sorted IDs, so parts[1] and parts[2] are sorted
        // We need to determine which one is buyer and which one is seller
        $id1 = (int)$parts[1];
        $id2 = (int)$parts[2];
        $carId = isset($parts[3]) ? (int)$parts[3] : null;
        
        // Since chat ID is sorted, we need to find which ID belongs to current user
        // The other ID is the one we want to show
        $otherUserId = null;
        $buyerId = null;
        $sellerId = null;
        
        if ($currentUserId == $id1) {
            // Current user is id1, so other user is id2
            $otherUserId = $id2;
        } elseif ($currentUserId == $id2) {
            // Current user is id2, so other user is id1
            $otherUserId = $id1;
        } else {
            // Current user is not in this chat (shouldn't happen, but handle it)
            return null;
        }
        
        // Get the other user
        $otherUser = \App\Models\User::find($otherUserId);
        
        if (!$otherUser) {
            return null;
        }
        
        // Determine buyer and seller IDs based on roles
        // Get both users to determine their roles
        $user1 = \App\Models\User::find($id1);
        $user2 = \App\Models\User::find($id2);
        
        if ($user1 && $user2) {
            if ($user1->role === 'buyer' && $user2->role === 'seller') {
                $buyerId = $id1;
                $sellerId = $id2;
            } elseif ($user1->role === 'seller' && $user2->role === 'buyer') {
                $buyerId = $id2;
                $sellerId = $id1;
            } else {
                // Fallback: use current user's role
                if ($role === 'buyer') {
                    $buyerId = $currentUserId;
                    $sellerId = $otherUserId;
                } else {
                    $buyerId = $otherUserId;
                    $sellerId = $currentUserId;
                }
            }
        } else {
            // Fallback: use current user's role
            if ($role === 'buyer') {
                $buyerId = $currentUserId;
                $sellerId = $otherUserId;
            } else {
                $buyerId = $otherUserId;
                $sellerId = $currentUserId;
            }
        }
        
        // Get buyer and seller objects
        $buyerObj = \App\Models\User::find($buyerId);
        $sellerObj = \App\Models\User::find($sellerId);
        
        $car = $carId ? \App\Models\car::find($carId) : null;
        
        // Get last message
        $lastMessage = $chatCacheData['last_message'] ?? '';
        $lastMessageAt = now();
        if (isset($chatCacheData['last_message_at'])) {
            try {
                $lastMessageAt = \Carbon\Carbon::parse($chatCacheData['last_message_at']);
            } catch (\Exception $e) {
                // Use now() as fallback
            }
        }
        $unreadCount = $chatCacheData['unread_count'] ?? 0;
        
        // IMPORTANT: Only include chats that have actual messages
        // Check if there are messages in cache for this chat
        $messagesCacheKey = 'chat_messages_' . $chatId;
        $messages = \Illuminate\Support\Facades\Cache::get($messagesCacheKey, []);
        
        // If no messages exist and no last_message, skip this chat
        if (empty($messages) && empty($lastMessage)) {
            return null;
        }
        
        // Only include if there's a valid last_message
        if (empty($lastMessage)) {
            return null;
        }
        
        // Get buyer and seller objects for proper access
        $buyerObj = \App\Models\User::find($buyerId);
        $sellerObj = \App\Models\User::find($sellerId);
        
        return (object)[
            'id' => $chatId,
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
            'car_id' => $carId,
            'buyer' => $buyerObj, // Always set buyer object
            'seller' => $sellerObj, // Always set seller object
            'other_user' => $otherUser, // The user we're chatting with (seller for buyer, buyer for seller)
            'other_user_id' => $otherUserId, // Other user ID for reference
            'car' => $car,
            'last_message' => (object)[
                'message' => $lastMessage,
                'created_at' => $lastMessageAt,
            ],
            'unread_count' => $unreadCount,
        ];
    })->filter(function($chat) {
        return $chat !== null;
    })->sortByDesc(function($chat) {
        return $chat->last_message->created_at;
    })->values();
    
    $unreadCount = $userChats->sum('unread_count');
@endphp

@if($role === 'buyer' || $role === 'seller')
<!-- Messages Widget -->
<div class="messages-widget-container">
    <!-- Messages Button -->
    <button class="messages-btn" id="messagesBtn" onclick="toggleMessagesModal()">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="messages-text">Messages</span>
        @if($unreadCount > 0)
        <span class="messages-badge">{{ $unreadCount }}</span>
        @endif
    </button>

    <!-- Messages Modal -->
    <div class="messages-modal" id="messagesModal">
        <div class="messages-modal-content">
            <div class="messages-modal-header">
                <h3 class="messages-modal-title">
                    Messages
                    @if($unreadCount > 0)
                    <span class="messages-badge-header">{{ $unreadCount }}</span>
                    @endif
                </h3>
                <div class="messages-modal-actions">
                    <button class="messages-modal-btn" onclick="maximizeMessages()" title="Maximize">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                        </svg>
                    </button>
                    <button class="messages-modal-btn" onclick="closeMessagesModal()" title="Close">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="messages-modal-body">
                <div class="messages-list" id="messagesList">
                    @if($userChats->count() > 0)
                        @foreach($userChats as $chat)
                            @php
                                // Get other user correctly based on role
                                // Use other_user if available (set in map function), otherwise fallback
                                $otherUser = $chat->other_user ?? null;
                                
                                // Debug: Log to ensure we have the right user
                                if (!$otherUser) {
                                    // Fallback: determine based on role and current user ID
                                    $currentUserId = Auth::id();
                                    
                                    if ($role === 'buyer') {
                                        // If buyer, show seller
                                        $otherUser = $chat->seller ?? null;
                                        if (!$otherUser && isset($chat->seller_id)) {
                                            $otherUser = \App\Models\User::find($chat->seller_id);
                                        }
                                        // Double check: make sure we're not showing buyer's own name
                                        if ($otherUser && $otherUser->id == $currentUserId) {
                                            // Wrong user, try seller
                                            $otherUser = $chat->seller ?? null;
                                            if (!$otherUser && isset($chat->seller_id)) {
                                                $otherUser = \App\Models\User::find($chat->seller_id);
                                            }
                                        }
                                    } else {
                                        // If seller, show buyer
                                        $otherUser = $chat->buyer ?? null;
                                        if (!$otherUser && isset($chat->buyer_id)) {
                                            $otherUser = \App\Models\User::find($chat->buyer_id);
                                        }
                                        // Double check: make sure we're not showing seller's own name
                                        if ($otherUser && $otherUser->id == $currentUserId) {
                                            // Wrong user, try buyer
                                            $otherUser = $chat->buyer ?? null;
                                            if (!$otherUser && isset($chat->buyer_id)) {
                                                $otherUser = \App\Models\User::find($chat->buyer_id);
                                            }
                                        }
                                    }
                                }
                                
                                // Final check: make sure otherUser is not current user
                                if ($otherUser && $otherUser->id == Auth::id()) {
                                    // This is wrong, skip this chat
                                    continue;
                                }
                                
                                if (!$otherUser) continue;
                                
                                $lastMessage = $chat->last_message ?? null;
                                $unreadChatCount = $chat->unread_count ?? 0;
                            @endphp
                            <div class="messages-item {{ $unreadChatCount > 0 ? 'messages-item-unread' : '' }}" 
                                 data-chat-id="{{ $chat->id }}"
                                 onclick="openMiniChat('{{ $chat->id }}', '{{ addslashes($otherUser->name) }}', {{ $otherUser->id }})">
                                <div class="messages-item-avatar">
                                    <span>{{ strtoupper(substr($otherUser->name, 0, 1)) }}</span>
                                </div>
                                <div class="messages-item-content">
                                <div class="messages-item-header">
                                    <span class="messages-item-name">{{ $otherUser->name }}</span>
                                    @if($lastMessage && isset($lastMessage->created_at))
                                    <span class="messages-item-time">{{ \Carbon\Carbon::parse($lastMessage->created_at)->diffForHumans() }}</span>
                                    @endif
                                </div>
                                    <div class="messages-item-preview">
                                        @if($lastMessage && isset($lastMessage->message) && $lastMessage->message)
                                        <span class="messages-item-message">{{ Str::limit($lastMessage->message, 40) }}</span>
                                        @else
                                        <span class="messages-item-message text-muted">Belum ada pesan</span>
                                        @endif
                                        @if($unreadChatCount > 0)
                                        <span class="messages-item-badge">{{ $unreadChatCount }}</span>
                                        @endif
                                    </div>
                                    @if(isset($chat->car) && $chat->car)
                                    <div class="messages-item-car">{{ $chat->car->brand ?? '' }} {{ $chat->car->nama ?? '' }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="messages-empty">
                            <i class="fas fa-comments"></i>
                            <p>Belum ada pesan</p>
                            <small>Mulai obrolan dengan {{ $role === 'buyer' ? 'penjual' : 'pembeli' }} dari halaman detail mobil</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mini Chat Widget -->
    <div class="mini-chat-widget" id="miniChatWidget" style="display: none;">
        <div class="mini-chat-header">
            <div class="mini-chat-user-info">
                <div class="mini-chat-avatar">
                    <span id="miniChatAvatar">U</span>
                </div>
                <div class="mini-chat-user-details">
                    <span class="mini-chat-user-name" id="miniChatUserName">User</span>
                    <span class="mini-chat-user-status" id="miniChatUserStatus">Active</span>
                </div>
            </div>
            <div class="mini-chat-actions">
                <button class="mini-chat-btn" onclick="openFullscreenChat()" title="Fullscreen">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                    </svg>
                </button>
                <button class="mini-chat-btn" onclick="minimizeMiniChat()" title="Minimize">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
                <button class="mini-chat-btn" onclick="closeMiniChat()" title="Close">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
        <div class="mini-chat-body" id="miniChatBody">
            <div class="mini-chat-messages" id="miniChatMessages">
                <!-- Messages will be loaded here -->
            </div>
        </div>
        <div class="mini-chat-footer">
            <form id="miniChatForm" onsubmit="return sendMiniChatMessage(event)">
                <div class="mini-chat-input-wrapper">
                    <button type="button" class="mini-chat-emoji-btn" onclick="toggleEmojiPicker()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                    </button>
                    <input type="text" 
                           id="miniChatInput" 
                           class="mini-chat-input" 
                           placeholder="Message..." 
                           autocomplete="off">
                    <button type="button" class="mini-chat-attach-btn" onclick="attachFile()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                        </svg>
                    </button>
                    <button type="submit" class="mini-chat-send-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Messages Button */
.messages-widget-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.messages-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #2d2d2d, #1a1a1a);
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    position: relative;
}

.messages-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
    background: linear-gradient(135deg, #3a3a3a, #252525);
}

.messages-btn svg {
    width: 18px;
    height: 18px;
    stroke: #fff;
}

.messages-text {
    font-weight: 600;
}

.messages-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #dc2626;
    color: #fff;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    border: 2px solid #1a1a1a;
}

/* Messages Modal */
.messages-modal {
    display: none;
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 380px;
    max-height: 600px;
    background: #1a1a1a;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    z-index: 1001;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

.messages-modal.active {
    display: block;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.messages-modal-content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.messages-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: #2d2d2d;
    border-bottom: 1px solid #3a3a3a;
}

.messages-modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.messages-badge-header {
    background: #dc2626;
    color: #fff;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 12px;
    font-weight: 700;
}

.messages-modal-actions {
    display: flex;
    gap: 8px;
}

.messages-modal-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #999;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.messages-modal-btn:hover {
    background: #3a3a3a;
    color: #fff;
}

.messages-modal-body {
    flex: 1;
    overflow-y: auto;
    background: #1a1a1a;
}

.messages-list {
    padding: 0;
}

.messages-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #2d2d2d;
    cursor: pointer;
    transition: all 0.2s;
}

.messages-item:hover {
    background: #2d2d2d;
}

.messages-item-unread {
    background: #252525;
    font-weight: 600;
}

.messages-item-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    margin-right: 12px;
    flex-shrink: 0;
}

.messages-item-content {
    flex: 1;
    min-width: 0;
}

.messages-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.messages-item-name {
    font-size: 15px;
    font-weight: 600;
    color: #fff;
}

.messages-item-time {
    font-size: 11px;
    color: #999;
    white-space: nowrap;
    margin-left: 8px;
}

.messages-item-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.messages-item-message {
    font-size: 13px;
    color: #999;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
}

.messages-item-unread .messages-item-message {
    color: #fff;
}

.messages-item-car {
    font-size: 11px;
    color: #777;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.messages-item-badge {
    background: #3b82f6;
    color: #fff;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 10px;
    font-weight: 700;
    min-width: 18px;
    text-align: center;
    flex-shrink: 0;
}

.messages-empty {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.messages-empty i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.messages-empty p {
    font-size: 16px;
    margin-bottom: 8px;
    color: #fff;
}

.messages-empty small {
    font-size: 13px;
    color: #666;
}

/* Mini Chat Widget */
.mini-chat-widget {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 350px;
    height: 500px;
    background: #1a1a1a;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    z-index: 1002;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

.mini-chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: #2d2d2d;
    border-bottom: 1px solid #3a3a3a;
}

.mini-chat-user-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.mini-chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
}

.mini-chat-user-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.mini-chat-user-name {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
}

.mini-chat-user-status {
    font-size: 11px;
    color: #999;
}

.mini-chat-actions {
    display: flex;
    gap: 4px;
}

.mini-chat-btn {
    width: 28px;
    height: 28px;
    border: none;
    background: transparent;
    color: #999;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.mini-chat-btn:hover {
    background: #3a3a3a;
    color: #fff;
}

.mini-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #1a1a1a;
}

.mini-chat-messages {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.mini-chat-message {
    display: flex;
    flex-direction: column;
    gap: 4px;
    max-width: 75%;
}

.mini-chat-message.sent {
    align-self: flex-end;
    align-items: flex-end;
}

.mini-chat-message.received {
    align-self: flex-start;
    align-items: flex-start;
}

.mini-chat-message-bubble {
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
}

.mini-chat-message.sent .mini-chat-message-bubble {
    background: #dc2626;
    color: #fff;
    border-bottom-right-radius: 4px;
}

.mini-chat-message.received .mini-chat-message-bubble {
    background: #2d2d2d;
    color: #fff;
    border-bottom-left-radius: 4px;
}

.mini-chat-message-time {
    font-size: 10px;
    color: #666;
    padding: 0 4px;
}

.mini-chat-footer {
    padding: 12px 16px;
    background: #2d2d2d;
    border-top: 1px solid #3a3a3a;
}

.mini-chat-input-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #1a1a1a;
    border-radius: 24px;
    padding: 6px 12px;
}

.mini-chat-emoji-btn,
.mini-chat-attach-btn,
.mini-chat-send-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #999;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}

.mini-chat-emoji-btn:hover,
.mini-chat-attach-btn:hover {
    background: #2d2d2d;
    color: #fff;
}

.mini-chat-send-btn {
    background: #dc2626;
    color: #fff;
}

.mini-chat-send-btn:hover {
    background: #b91c1c;
    transform: scale(1.05);
}

.mini-chat-input {
    flex: 1;
    border: none;
    background: transparent;
    color: #fff;
    font-size: 14px;
    outline: none;
    padding: 4px 8px;
}

.mini-chat-input::placeholder {
    color: #666;
}

/* Scrollbar */
.messages-modal-body::-webkit-scrollbar,
.mini-chat-body::-webkit-scrollbar {
    width: 6px;
}

.messages-modal-body::-webkit-scrollbar-track,
.mini-chat-body::-webkit-scrollbar-track {
    background: #1a1a1a;
}

.messages-modal-body::-webkit-scrollbar-thumb,
.mini-chat-body::-webkit-scrollbar-thumb {
    background: #3a3a3a;
    border-radius: 3px;
}

.messages-modal-body::-webkit-scrollbar-thumb:hover,
.mini-chat-body::-webkit-scrollbar-thumb:hover {
    background: #4a4a4a;
}

/* Responsive */
@media (max-width: 768px) {
    .messages-modal {
        width: calc(100% - 40px);
        right: 20px;
        left: 20px;
    }
    
    .mini-chat-widget {
        width: calc(100% - 40px);
        right: 20px;
        left: 20px;
    }
}
</style>

<script>
let currentChatId = null;
let currentOtherUserId = null;
let currentOtherUserName = null;
let messagesInterval = null;
let miniChatChannel = null;
let miniChatPresence = null;

function toggleMessagesModal() {
    const modal = document.getElementById('messagesModal');
    modal.classList.toggle('active');
}

function closeMessagesModal() {
    const modal = document.getElementById('messagesModal');
    modal.classList.remove('active');
}

function maximizeMessages() {
    // Redirect to full chat page
    const route = '{{ $role === "buyer" ? route("chat.index") : route("chat.seller.index") }}';
    window.location.href = route;
}

function openMiniChat(chatId, userName, otherUserId) {
    currentChatId = chatId;
    currentOtherUserId = otherUserId;
    currentOtherUserName = userName;
    
    console.log('Opening mini chat:', {
        chatId: chatId,
        userName: userName,
        otherUserId: otherUserId
    });
    
    // Update mini chat header with other user's name (not current user)
    const userNameEl = document.getElementById('miniChatUserName');
    const avatarEl = document.getElementById('miniChatAvatar');
    
    if (userNameEl && userName) {
        userNameEl.textContent = userName;
        console.log('Updated mini chat user name to:', userName);
    } else {
        console.error('Could not update user name:', { userNameEl, userName });
    }
    
    if (avatarEl && userName) {
        avatarEl.textContent = userName.charAt(0).toUpperCase();
        console.log('Updated mini chat avatar to:', userName.charAt(0).toUpperCase());
    } else {
        console.error('Could not update avatar:', { avatarEl, userName });
    }
    
    // Show mini chat
    const miniChatWidget = document.getElementById('miniChatWidget');
    if (miniChatWidget) {
        miniChatWidget.style.display = 'flex';
    }
    
    // Close messages modal
    closeMessagesModal();
    
    // Load messages
    loadMiniChatMessages();
    
    // Setup realtime listeners or fallback to polling
    const realtimeReady = setupMiniChatRealtime(chatId, otherUserId);
    if (!realtimeReady) {
        if (messagesInterval) {
            clearInterval(messagesInterval);
        }
        messagesInterval = setInterval(loadMiniChatMessages, 3000);
    }
    
    // Focus on input
    setTimeout(() => {
        const input = document.getElementById('miniChatInput');
        if (input) input.focus();
    }, 100);
}

function closeMiniChat() {
    const miniChatWidget = document.getElementById('miniChatWidget');
    if (miniChatWidget) {
        miniChatWidget.style.display = 'none';
    }
    
    currentChatId = null;
    currentOtherUserId = null;
    currentOtherUserName = null;
    
    if (messagesInterval) {
        clearInterval(messagesInterval);
        messagesInterval = null;
    }
    if (miniChatChannel && window.Echo) {
        try { window.Echo.leave(`chat.${currentChatId}`); } catch (_) {}
        miniChatChannel = null;
    }
    if (miniChatPresence && window.Echo) {
        try { window.Echo.leave(`presence-chat.${currentChatId}`); } catch (_) {}
        miniChatPresence = null;
    }
    
    // Clear messages
    const messagesContainer = document.getElementById('miniChatMessages');
    if (messagesContainer) {
        messagesContainer.innerHTML = '';
    }
}

function minimizeMiniChat() {
    closeMiniChat();
}

function openFullscreenChat() {
    if (!currentChatId) return;
    
    // Redirect to full chat page
    window.location.href = `/chat/${currentChatId}`;
}

function loadMiniChatMessages() {
    if (!currentChatId) {
        console.warn('No chat ID available for loading messages');
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    const encodedChatId = encodeURIComponent(currentChatId);
    
    fetch(`/chat/${encodedChatId}/messages`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                try {
                    const json = JSON.parse(text);
                    throw new Error(json.error || 'Network response was not ok');
                } catch (e) {
                    throw new Error(text || 'Network response was not ok');
                }
            });
        }
        return response.json();
    })
    .then(data => {
        const messagesContainer = document.getElementById('miniChatMessages');
        if (!messagesContainer) {
            console.error('Messages container not found');
            return;
        }
        
        // Store current scroll position
        const chatBody = document.getElementById('miniChatBody');
        const wasAtBottom = chatBody ? 
            (chatBody.scrollHeight - chatBody.scrollTop <= chatBody.clientHeight + 50) : true;
        
        messagesContainer.innerHTML = '';
        
        if (data.messages && Array.isArray(data.messages) && data.messages.length > 0) {
            data.messages.forEach(message => {
                if (!message) return;
                
                const isSent = parseInt(message.sender_id) === parseInt({{ Auth::id() }});
                const messageDiv = document.createElement('div');
                messageDiv.className = `mini-chat-message ${isSent ? 'sent' : 'received'}`;
                messageDiv.dataset.messageId = message.id || '';
                
                const bubble = document.createElement('div');
                bubble.className = 'mini-chat-message-bubble';
                const isDeleted = !!(message.is_deleted) || !message.message;
                bubble.textContent = isDeleted ? 'Pesan ini dihapus' : (message.message || '');
                
                const time = document.createElement('div');
                time.className = 'mini-chat-message-time';
                try {
                    if (message.created_at) {
                        const messageDate = new Date(message.created_at);
                        if (!isNaN(messageDate.getTime())) {
                            time.textContent = messageDate.toLocaleTimeString('id-ID', { 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });
                        } else {
                            time.textContent = 'Sekarang';
                        }
                    } else {
                        time.textContent = 'Sekarang';
                    }
                } catch (e) {
                    console.warn('Error parsing date:', e);
                    time.textContent = 'Sekarang';
                }
                
                messageDiv.appendChild(bubble);
                messageDiv.appendChild(time);
                messagesContainer.appendChild(messageDiv);
            });
            
            // Scroll to bottom only if was at bottom before
            if (chatBody && wasAtBottom) {
                setTimeout(() => {
                    chatBody.scrollTop = chatBody.scrollHeight;
                }, 100);
            }
        } else {
            // Show empty state
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'mini-chat-empty';
            emptyDiv.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Belum ada pesan</p>';
            messagesContainer.appendChild(emptyDiv);
        }
    })
    .catch(error => {
        console.error('Error loading messages:', error);
        const messagesContainer = document.getElementById('miniChatMessages');
        if (messagesContainer && messagesContainer.children.length === 0) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mini-chat-error';
            errorDiv.innerHTML = '<p style="text-align: center; color: #dc2626; padding: 20px;">Gagal memuat pesan: ' + error.message + '</p>';
            messagesContainer.appendChild(errorDiv);
        }
    });
}

function setupMiniChatRealtime(chatId, otherUserId) {
    if (typeof Echo === 'undefined' || !window.Echo) return false;
    try {
        miniChatChannel = window.Echo.private(`chat.${chatId}`);
        miniChatPresence = window.Echo.join(`presence-chat.${chatId}`);
    } catch (e) {
        console.warn('Realtime setup failed, fallback to polling', e);
        return false;
    }
    miniChatPresence.here((users) => {
        const otherOnline = users.some(u => parseInt(u.id) === parseInt(otherUserId));
        const statusEl = document.getElementById('miniChatUserStatus');
        if (statusEl) statusEl.textContent = otherOnline ? 'Online' : 'Offline';
    });
    miniChatPresence.joining((user) => {
        if (parseInt(user.id) === parseInt(otherUserId)) {
            const statusEl = document.getElementById('miniChatUserStatus');
            if (statusEl) statusEl.textContent = 'Online';
        }
    });
    miniChatPresence.leaving((user) => {
        if (parseInt(user.id) === parseInt(otherUserId)) {
            const statusEl = document.getElementById('miniChatUserStatus');
            if (statusEl) statusEl.textContent = 'Offline';
        }
    });
    const handleSent = (e) => {
        if (!e.message) return;
        appendMiniChatMessage(e.message);
        updateChatListPreview(chatId, e.message);
    };
    miniChatChannel.listen('.MessageSent', handleSent);
    miniChatChannel.listen('MessageSent', handleSent);
    const handleUpdated = (e) => {
        if (!e.message) return;
        updateMiniMessageUI(e.message.id, e.message.message);
        updateChatListPreview(chatId, e.message);
    };
    miniChatChannel.listen('.MessageUpdated', handleUpdated);
    miniChatChannel.listen('MessageUpdated', handleUpdated);
    const handleDeleted = (e) => {
        if (!e.message_id) return;
        markMiniMessageDeletedUI(e.message_id);
        updateChatListPreview(chatId, { id: e.message_id, message: '', is_deleted: true, created_at: new Date().toISOString(), sender_id: otherUserId });
    };
    miniChatChannel.listen('.MessageDeleted', handleDeleted);
    miniChatChannel.listen('MessageDeleted', handleDeleted);
    return true;
}

function appendMiniChatMessage(msg) {
    const messagesContainer = document.getElementById('miniChatMessages');
    if (!messagesContainer) return;
    const isSent = parseInt(msg.sender_id) === parseInt({{ Auth::id() }});
    const messageDiv = document.createElement('div');
    messageDiv.className = `mini-chat-message ${isSent ? 'sent' : 'received'}`;
    if (msg.id) messageDiv.dataset.messageId = msg.id;
    const bubble = document.createElement('div');
    bubble.className = 'mini-chat-message-bubble';
    const isDeleted = !!(msg.is_deleted) || !msg.message;
    bubble.textContent = isDeleted ? 'Pesan ini dihapus' : (msg.message || '');
    const time = document.createElement('div');
    time.className = 'mini-chat-message-time';
    try {
        const d = new Date(msg.created_at);
        time.textContent = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    } catch (_) {
        time.textContent = 'Sekarang';
    }
    messageDiv.appendChild(bubble);
    messageDiv.appendChild(time);
    messagesContainer.appendChild(messageDiv);
    const chatBody = document.getElementById('miniChatBody');
    if (chatBody) setTimeout(() => { chatBody.scrollTop = chatBody.scrollHeight; }, 100);
}

function updateMiniMessageUI(messageId, newText) {
    const bubble = document.querySelector(`.mini-chat-message[data-message-id="${messageId}"] .mini-chat-message-bubble`);
    if (!bubble) return;
    bubble.textContent = newText || '';
}

function markMiniMessageDeletedUI(messageId) {
    const bubble = document.querySelector(`.mini-chat-message[data-message-id="${messageId}"] .mini-chat-message-bubble`);
    if (!bubble) return;
    bubble.textContent = 'Pesan ini dihapus';
}

function updateChatListPreview(chatId, message) {
    const item = document.querySelector(`.messages-item[data-chat-id="${chatId}"]`);
    if (!item) return;
    const preview = item.querySelector('.messages-item-message');
    const timeEl = item.querySelector('.messages-item-time');
    const badge = item.querySelector('.messages-item-badge');
    const isDeleted = !!(message.is_deleted) || !message.message;
    if (preview) preview.textContent = isDeleted ? 'Pesan ini dihapus' : (message.message || '');
    if (timeEl && message.created_at) {
        try {
            const d = new Date(message.created_at);
            timeEl.textContent = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        } catch (_) {}
    }
    if (badge && parseInt(message.sender_id) !== parseInt({{ Auth::id() }})) {
        const current = parseInt(badge.textContent || '0');
        badge.textContent = String(current + 1);
        item.classList.add('messages-item-unread');
    }
}

function refreshUnreadCounts() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    fetch('/chat/unread-count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.ok ? res.json() : Promise.reject())
    .then(data => {
        const total = parseInt(data.total || 0);
        const btn = document.getElementById('messagesBtn');
        const modalTitle = document.querySelector('.messages-modal-title');
        let badgeBtn = btn?.querySelector('.messages-badge');
        let badgeHeader = modalTitle?.querySelector('.messages-badge-header');
        if (total > 0) {
            if (!badgeBtn && btn) {
                badgeBtn = document.createElement('span');
                badgeBtn.className = 'messages-badge';
                btn.appendChild(badgeBtn);
            }
            if (!badgeHeader && modalTitle) {
                badgeHeader = document.createElement('span');
                badgeHeader.className = 'messages-badge-header';
                modalTitle.appendChild(badgeHeader);
            }
            if (badgeBtn) badgeBtn.textContent = total;
            if (badgeHeader) badgeHeader.textContent = total;
        } else {
            if (badgeBtn) badgeBtn.remove();
            if (badgeHeader) badgeHeader.remove();
        }

        const perChat = data.per_chat || {};
        document.querySelectorAll('.messages-item').forEach(item => {
            const chatId = item.getAttribute('data-chat-id');
            const meta = chatId && perChat[chatId] ? perChat[chatId] : null;
            const count = meta ? parseInt(meta.unread_count || 0) : 0;
            let badge = item.querySelector('.messages-item-badge');
            const preview = item.querySelector('.messages-item-message');
            const timeEl = item.querySelector('.messages-item-time');
            if (meta && preview && meta.last_message) {
                preview.textContent = meta.last_message;
            }
            if (meta && timeEl && meta.last_message_time) {
                timeEl.textContent = meta.last_message_time;
            }
            if (count > 0) {
                item.classList.add('messages-item-unread');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'messages-item-badge';
                    const previewWrap = item.querySelector('.messages-item-preview');
                    if (previewWrap) previewWrap.appendChild(badge);
                }
                badge.textContent = count;
            } else {
                item.classList.remove('messages-item-unread');
                if (badge) badge.remove();
            }
        });
    })
    .catch(() => {});
}
function sendMiniChatMessage(event) {
    // Prevent default form submission
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    if (!currentChatId) {
        console.error('No chat ID available');
        return false;
    }
    
    const input = document.getElementById('miniChatInput');
    if (!input) {
        console.error('Input element not found');
        return false;
    }
    
    const message = input.value.trim();
    
    if (!message) {
        return false;
    }
    
    // Disable input while sending
    input.disabled = true;
    const form = document.getElementById('miniChatForm');
    const sendBtn = form ? form.querySelector('.mini-chat-send-btn') : null;
    if (sendBtn) {
        sendBtn.disabled = true;
        const originalHTML = sendBtn.innerHTML;
        sendBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>';
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        input.disabled = false;
        if (sendBtn) sendBtn.disabled = false;
        alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
        return false;
    }
    
    // Encode chat ID properly
    const encodedChatId = encodeURIComponent(currentChatId);
    
    fetch(`/chat/${encodedChatId}/message`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                try {
                    const json = JSON.parse(text);
                    throw new Error(json.error || 'Network response was not ok');
                } catch (e) {
                    throw new Error(text || 'Network response was not ok');
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            input.value = '';
            
            // Add message immediately to UI for better UX
            const messagesContainer = document.getElementById('miniChatMessages');
            if (messagesContainer && data.message) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'mini-chat-message sent';
                
                const bubble = document.createElement('div');
                bubble.className = 'mini-chat-message-bubble';
                bubble.textContent = data.message.message || message;
                
                const time = document.createElement('div');
                time.className = 'mini-chat-message-time';
                try {
                    const messageDate = new Date(data.message.created_at);
                    time.textContent = messageDate.toLocaleTimeString('id-ID', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                } catch (e) {
                    time.textContent = 'Sekarang';
                }
                
                messageDiv.appendChild(bubble);
                messageDiv.appendChild(time);
                messagesContainer.appendChild(messageDiv);
                
                // Scroll to bottom
                const chatBody = document.getElementById('miniChatBody');
                if (chatBody) {
                    setTimeout(() => {
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }, 100);
                }
            }
            
            // Reload messages after a short delay to get updated data
            setTimeout(loadMiniChatMessages, 1000);
        } else {
            console.error('Failed to send message:', data);
            alert('Gagal mengirim pesan: ' + (data.error || 'Silakan coba lagi.'));
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Terjadi kesalahan saat mengirim pesan: ' + error.message);
    })
    .finally(() => {
        // Re-enable input
        input.disabled = false;
        if (sendBtn) {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>';
        }
        input.focus();
    });
    
    // Always return false to prevent form submission
    return false;
}

function toggleEmojiPicker() {
    // Emoji picker functionality can be added here
    console.log('Emoji picker clicked');
}

function attachFile() {
    // File attachment functionality can be added here
    console.log('Attach file clicked');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('messagesModal');
    const btn = document.getElementById('messagesBtn');
    
    if (modal && modal.classList.contains('active')) {
        if (!modal.contains(event.target) && !btn.contains(event.target)) {
            closeMessagesModal();
        }
    }
});

// Handle Enter key in mini chat input
document.addEventListener('DOMContentLoaded', function() {
    const miniChatInput = document.getElementById('miniChatInput');
    if (miniChatInput) {
        miniChatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                e.stopPropagation();
                sendMiniChatMessage(e);
            }
        });
    }
    
    // Also attach event listener to form to ensure preventDefault works
    const miniChatForm = document.getElementById('miniChatForm');
    if (miniChatForm) {
        miniChatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sendMiniChatMessage(e);
            return false;
        });
    }
    refreshUnreadCounts();
    setInterval(refreshUnreadCounts, 8000);
});

// Ensure form doesn't cause page refresh
window.addEventListener('load', function() {
    const miniChatForm = document.getElementById('miniChatForm');
    if (miniChatForm) {
        // Remove any existing event listeners and add fresh one
        const newForm = miniChatForm.cloneNode(true);
        miniChatForm.parentNode.replaceChild(newForm, miniChatForm);
        
        // Attach event listener
        newForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            sendMiniChatMessage(e);
            return false;
        }, true);
        
        // Also handle onsubmit attribute
        newForm.onsubmit = function(e) {
            e.preventDefault();
            e.stopPropagation();
            sendMiniChatMessage(e);
            return false;
        };
    }
});
</script>
@endif
@endauth
