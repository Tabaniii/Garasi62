<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    /**
     * Show chat with specific seller (for buyer)
     */
    public function showSeller($sellerId)
    {
        $buyer = Auth::user();
        
        if ($buyer->role !== 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Hanya buyer yang dapat mengakses chat.');
        }

        $seller = User::findOrFail($sellerId);
        
        if ($seller->role !== 'seller') {
            return redirect()->back()->with('error', 'User yang dipilih bukan seller.');
        }

        $carId = request()->query('car_id');
        $car = $carId ? car::find($carId) : null;

        // Generate chat ID from combination
        $chatId = $this->generateChatId($buyer->id, $seller->id, $carId);
        
        // Create virtual chat object
        $chat = (object)[
            'id' => $chatId,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'car_id' => $carId,
        ];

        // Load messages from cache (not database)
        $messages = $this->getChatMessages($chatId);

        // Set otherUser for consistency
        $otherUser = $seller;
        $user = $buyer;

        // Pass Pusher config for frontend
        $pusherConfig = [
            'key' => config('broadcasting.connections.pusher.key'),
            'cluster' => config('broadcasting.connections.pusher.options.cluster', 'ap1'),
        ];

        // If AJAX request, return only the content without layout
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('chat.show', compact('chat', 'otherUser', 'user', 'car', 'messages', 'pusherConfig'))->render(),
            ]);
        }

        return view('chat.show', compact('chat', 'otherUser', 'user', 'car', 'messages', 'pusherConfig'));
    }

    /**
     * Show chat by chat ID (for both buyer and seller)
     */
    public function show($chatId)
    {
        $user = Auth::user();
        
        // Parse chat ID to get buyer_id, seller_id, car_id
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => 'Chat tidak ditemukan.'], 404);
            }
            return redirect()->route('dashboard')->with('error', 'Chat tidak ditemukan.');
        }

        // Verify user is part of this chat
        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => 'Anda tidak memiliki akses ke chat ini.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke chat ini.');
        }

        // Get other user
        $otherUserId = $chatData['buyer_id'] == $user->id ? $chatData['seller_id'] : $chatData['buyer_id'];
        $otherUser = User::findOrFail($otherUserId);
        
        $car = $chatData['car_id'] ? car::find($chatData['car_id']) : null;

        // Create virtual chat object
        $chat = (object)[
            'id' => $chatId,
            'buyer_id' => $chatData['buyer_id'],
            'seller_id' => $chatData['seller_id'],
            'car_id' => $chatData['car_id'],
        ];

        // Load messages from cache (not database)
        $messages = $this->getChatMessages($chatId);

        // Pass Pusher config for frontend
        $pusherConfig = [
            'key' => config('broadcasting.connections.pusher.key'),
            'cluster' => config('broadcasting.connections.pusher.options.cluster', 'ap1'),
        ];

        // If AJAX request, return only the content without layout
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('chat.show', compact('chat', 'otherUser', 'user', 'car', 'messages', 'pusherConfig'))->render(),
            ]);
        }

        return view('chat.show', compact('chat', 'otherUser', 'user', 'car', 'messages', 'pusherConfig'));
    }

    /**
     * Get list of chats for buyer
     * Load from cache (recent chats with messages)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Hanya buyer yang dapat mengakses chat.');
        }

        // Get recent chats from cache
        $chats = $this->getUserChats($user->id, 'buyer');

        return view('chat.index', compact('chats'));
    }

    /**
     * Get list of chats for seller
     * Load from cache (recent chats with messages)
     */
    public function sellerIndex()
    {
        $user = Auth::user();

        if ($user->role !== 'seller') {
            return redirect()->route('dashboard')->with('error', 'Hanya seller yang dapat mengakses chat.');
        }

        // Get recent chats from cache
        $chats = $this->getUserChats($user->id, 'seller');

        return view('chat.seller-index', compact('chats'));
    }

    /**
     * Store a new message (save to cache, broadcast real-time)
     */
    public function store(Request $request, $chatId)
    {
        $user = Auth::user();
        
        // Parse chat ID to get buyer_id, seller_id, car_id
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        // Verify user is part of this chat
        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
            'reply_to' => 'nullable|string',
        ]);

        // Create virtual chat object
        $chat = (object)[
            'id' => $chatId,
            'buyer_id' => $chatData['buyer_id'],
            'seller_id' => $chatData['seller_id'],
            'car_id' => $chatData['car_id'],
        ];

        // Create message object
        $messageId = time() . '_' . uniqid();
        $message = (object)[
            'id' => $messageId,
            'chat_id' => $chatId,
            'sender_id' => $user->id,
            'sender' => $user,
            'sender_name' => $user->name, // Add sender_name directly
            'message' => $request->message,
            'reply_to' => $request->reply_to ?? null,
            'created_at' => now(),
        ];
        
        \Log::info('Broadcasting message', [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'sender_id' => $user->id,
            'sender_name' => $user->name,
        ]);

        // Save message to cache (not database)
        $this->saveMessageToCache($chatId, $message);

        // Update chat list cache
        $this->updateChatListCache($chatData['buyer_id'], $chatData['seller_id'], $chatData['car_id'], $message);

        // Broadcast the message to both participants (real-time)
        try {
            \Log::info('Broadcasting message event', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'sender_id' => $user->id,
            ]);
            
            event(new \App\Events\MessageSent($message, $chat));
            
            // Broadcast notification to recipient (not sender)
            $recipientId = $chatData['buyer_id'] == $user->id ? $chatData['seller_id'] : $chatData['buyer_id'];
            event(new \App\Events\NewChatMessage($message, $chat, $recipientId));
            
            \Log::info('Message broadcasted successfully', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast message', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Continue anyway - message is saved in cache
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'chat_id' => $message->chat_id,
                'sender_id' => $message->sender_id,
                'sender_name' => $user->name, // Add sender_name directly
                'sender' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'message' => $message->message,
                'created_at' => $message->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Get messages for a chat (AJAX)
     * Load from cache (not database)
     */
    public function getMessages($chatId)
    {
        $user = Auth::user();
        
        // Parse chat ID to verify
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        // Verify user is part of this chat
        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Load messages from cache
        $messages = $this->getChatMessages($chatId);

        return response()->json([
            'messages' => $messages->values()->all(),
        ]);
    }

    /**
     * Generate unique chat ID from buyer_id, seller_id, car_id
     */
    private function generateChatId($buyerId, $sellerId, $carId = null)
    {
        // Sort IDs to ensure same chat always has same ID regardless of who initiates
        $ids = [$buyerId, $sellerId];
        sort($ids);
        
        $chatId = 'chat_' . $ids[0] . '_' . $ids[1];
        if ($carId) {
            $chatId .= '_' . $carId;
        }
        
        return $chatId;
    }

    /**
     * Parse chat ID to get buyer_id, seller_id, car_id
     */
    private function parseChatId($chatId)
    {
        // Format: chat_buyerId_sellerId_carId or chat_buyerId_sellerId
        if (!str_starts_with($chatId, 'chat_')) {
            return null;
        }
        
        $parts = explode('_', $chatId);
        if (count($parts) < 3) {
            return null;
        }
        
        $buyerId = (int)$parts[1];
        $sellerId = (int)$parts[2];
        $carId = isset($parts[3]) ? (int)$parts[3] : null;
        
        return [
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
            'car_id' => $carId,
        ];
    }

    /**
     * Get chat messages from cache
     */
    private function getChatMessages($chatId)
    {
        $cacheKey = 'chat_messages_' . $chatId;
        $messages = Cache::get($cacheKey, []);
        
        return collect($messages)->map(function($msg) {
            return (object)$msg;
        });
    }

    /**
     * Save message to cache
     */
    private function saveMessageToCache($chatId, $message)
    {
        $cacheKey = 'chat_messages_' . $chatId;
        $messages = Cache::get($cacheKey, []);
        
        // Get sender name safely
        $senderName = 'User';
        if (isset($message->sender_name)) {
            $senderName = $message->sender_name;
        } elseif (isset($message->sender) && is_object($message->sender)) {
            $senderName = $message->sender->name ?? 'User';
        }
        
        // Get created_at safely
        $createdAt = now();
        if (isset($message->created_at)) {
            if (is_object($message->created_at)) {
                $createdAt = $message->created_at;
            } else {
                $createdAt = \Carbon\Carbon::parse($message->created_at);
            }
        }
        
        // Get edited_at safely
        $editedAt = null;
        if (isset($message->edited_at)) {
            if (is_object($message->edited_at)) {
                $editedAt = $message->edited_at->toIso8601String();
            } else {
                $editedAt = $message->edited_at;
            }
        }

        // Get deleted_at safely
        $deletedAt = null;
        if (isset($message->deleted_at)) {
            if (is_object($message->deleted_at)) {
                $deletedAt = $message->deleted_at->toIso8601String();
            } else {
                $deletedAt = $message->deleted_at;
            }
        }

        // Add new message
        // CRITICAL: New messages must always have empty read_by array
        // This ensures sender's own messages are never marked as read until recipient opens chat
        $messages[] = [
            'id' => $message->id,
            'chat_id' => $message->chat_id,
            'sender_id' => $message->sender_id,
            'sender_name' => $senderName,
            'message' => $message->message,
            'reply_to' => $message->reply_to ?? null,
            'edited_at' => $editedAt,
            'deleted_at' => $deletedAt,
            'is_deleted_for_sender' => $message->is_deleted_for_sender ?? false,
            'is_read' => false, // Always false for new messages
            'read_by' => [], // Always empty array for new messages - will be populated when recipient reads
            'created_at' => $createdAt->toIso8601String(),
        ];
        
        // Keep only last 100 messages per chat (to prevent cache from growing too large)
        if (count($messages) > 100) {
            $messages = array_slice($messages, -100);
        }
        
        // Store in cache for 7 days
        Cache::put($cacheKey, $messages, now()->addDays(7));
    }

    /**
     * Update chat list cache for both users
     */
    private function updateChatListCache($buyerId, $sellerId, $carId, $message)
    {
        // Get created_at safely
        $createdAt = now();
        if (isset($message->created_at)) {
            if (is_object($message->created_at)) {
                $createdAt = $message->created_at;
            } else {
                $createdAt = \Carbon\Carbon::parse($message->created_at);
            }
        }
        $createdAtString = $createdAt->toIso8601String();
        
        $chatId = $this->generateChatId($buyerId, $sellerId, $carId);
        
        // Update buyer's chat list
        $buyerChatsKey = 'user_chats_' . $buyerId;
        $buyerChats = Cache::get($buyerChatsKey, []);
        
        // Preserve existing unread_count or set to 0 if sender is buyer
        $existingBuyerUnread = isset($buyerChats[$chatId]['unread_count']) ? $buyerChats[$chatId]['unread_count'] : 0;
        $buyerUnreadCount = $message->sender_id == $buyerId ? 0 : ($existingBuyerUnread + 1);
        
        $buyerChats[$chatId] = [
            'chat_id' => $chatId,
            'seller_id' => $sellerId,
            'car_id' => $carId,
            'last_message' => $message->message,
            'last_message_at' => $createdAtString,
            'unread_count' => $buyerUnreadCount,
        ];
        
        // Keep only last 50 chats
        if (count($buyerChats) > 50) {
            $buyerChats = array_slice($buyerChats, -50, 50, true);
        }
        
        Cache::put($buyerChatsKey, $buyerChats, now()->addDays(7));
        
        // Update seller's chat list
        $sellerChatsKey = 'user_chats_' . $sellerId;
        $sellerChats = Cache::get($sellerChatsKey, []);
        
        // Preserve existing unread_count or set to 0 if sender is seller
        $existingSellerUnread = isset($sellerChats[$chatId]['unread_count']) ? $sellerChats[$chatId]['unread_count'] : 0;
        $sellerUnreadCount = $message->sender_id == $sellerId ? 0 : ($existingSellerUnread + 1);
        
        $sellerChats[$chatId] = [
            'chat_id' => $chatId,
            'buyer_id' => $buyerId,
            'car_id' => $carId,
            'last_message' => $message->message,
            'last_message_at' => $createdAtString,
            'unread_count' => $sellerUnreadCount,
        ];
        
        // Keep only last 50 chats
        if (count($sellerChats) > 50) {
            $sellerChats = array_slice($sellerChats, -50, 50, true);
        }
        
        Cache::put($sellerChatsKey, $sellerChats, now()->addDays(7));
    }

    /**
     * Get user chats from cache
     */
    private function getUserChats($userId, $role)
    {
        $cacheKey = 'user_chats_' . $userId;
        $chatsData = Cache::get($cacheKey, []);
        
        // Debug: Log cache data
        if (empty($chatsData)) {
            \Log::info('getUserChats - Empty cache', [
                'userId' => $userId,
                'role' => $role,
                'cacheKey' => $cacheKey,
            ]);
            return collect([]);
        }
        
        $chats = collect($chatsData)->map(function($chatCacheData, $chatId) use ($role) {
            // Parse chat ID to get buyer_id, seller_id, car_id
            $chatData = $this->parseChatId($chatId);
            if (!$chatData) {
                \Log::warning('Failed to parse chat ID', ['chatId' => $chatId]);
                return null;
            }
            
            // Get other user based on role
            if ($role === 'buyer') {
                $otherUser = User::find($chatData['seller_id']);
            } else {
                $otherUser = User::find($chatData['buyer_id']);
            }
            
            if (!$otherUser) {
                \Log::warning('Other user not found', [
                    'role' => $role,
                    'buyer_id' => $chatData['buyer_id'],
                    'seller_id' => $chatData['seller_id'],
                ]);
                return null;
            }
            
            $car = $chatData['car_id'] ? car::find($chatData['car_id']) : null;
            
            // Get last message from cache data (use $chatCacheData directly)
            $lastMessage = $chatCacheData['last_message'] ?? '';
            $lastMessageAt = now();
            if (isset($chatCacheData['last_message_at'])) {
                try {
                    $lastMessageAt = \Carbon\Carbon::parse($chatCacheData['last_message_at']);
                } catch (\Exception $e) {
                    \Log::warning('Failed to parse last_message_at', [
                        'last_message_at' => $chatCacheData['last_message_at'] ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            $unreadCount = $chatCacheData['unread_count'] ?? 0;
            
            return (object)[
                'id' => $chatId,
                'buyer_id' => $chatData['buyer_id'],
                'seller_id' => $chatData['seller_id'],
                'car_id' => $chatData['car_id'],
                'seller' => $role === 'buyer' ? $otherUser : null,
                'buyer' => $role === 'seller' ? $otherUser : null,
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
        
        return $chats;
    }

    /**
     * Get unread chat count for current user
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        
        $cacheKey = 'user_chats_' . $user->id;
        $chatsData = Cache::get($cacheKey, []);
        
        $unreadCount = 0;
        foreach ($chatsData as $chatData) {
            $unreadCount += $chatData['unread_count'] ?? 0;
        }
        
        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark messages as read when user opens chat
     */
    public function markAsRead($chatId)
    {
        $user = Auth::user();
        
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark all messages from other user as read in cache
        // CRITICAL: Only mark messages from OTHER users, NEVER mark sender's own messages
        $cacheKey = 'chat_messages_' . $chatId;
        $allMessages = Cache::get($cacheKey, []);
        
        $updated = false;
        foreach ($allMessages as $key => $msg) {
            // CRITICAL CHECK: Only process messages from OTHER user (not sender)
            if (!isset($msg['sender_id']) || $msg['sender_id'] == $user->id) {
                // Skip sender's own messages - they should NEVER be marked as read
                // Also ensure sender's own messages have empty read_by array
                if (isset($msg['sender_id']) && $msg['sender_id'] == $user->id) {
                    // Force clear read_by for sender's own messages (safety check)
                    $allMessages[$key]['read_by'] = [];
                    $allMessages[$key]['is_read'] = false;
                }
                continue; // Skip to next message
            }
            
            // This is a message from OTHER user - safe to mark as read
            if (!isset($msg['read_by']) || !is_array($msg['read_by'])) {
                $allMessages[$key]['read_by'] = [];
            }
            
            $readBy = $allMessages[$key]['read_by'];
            // Only add current user to read_by if not already there
            if (!in_array($user->id, $readBy)) {
                $allMessages[$key]['read_by'][] = $user->id;
                $allMessages[$key]['is_read'] = true;
                $updated = true;
            }
        }
        
        if ($updated) {
            Cache::put($cacheKey, $allMessages, now()->addDays(7));
            
            // Update unread count in chat list cache
            $userChatsKey = 'user_chats_' . $user->id;
            $userChats = Cache::get($userChatsKey, []);
            if (isset($userChats[$chatId])) {
                $userChats[$chatId]['unread_count'] = 0;
                Cache::put($userChatsKey, $userChats, now()->addDays(7));
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
        ]);
    }

    /**
     * Reply to a message
     */
    public function reply(Request $request, $chatId)
    {
        $user = Auth::user();
        
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
            'reply_to' => 'required|string',
        ]);

        // Get replied to message from cache
        $messages = $this->getChatMessages($chatId);
        $repliedToMessage = $messages->firstWhere('id', $request->reply_to);
        
        if (!$repliedToMessage) {
            return response()->json(['error' => 'Message tidak ditemukan'], 404);
        }

        $chat = (object)[
            'id' => $chatId,
            'buyer_id' => $chatData['buyer_id'],
            'seller_id' => $chatData['seller_id'],
            'car_id' => $chatData['car_id'],
        ];

        $messageId = time() . '_' . uniqid();
        $message = (object)[
            'id' => $messageId,
            'chat_id' => $chatId,
            'sender_id' => $user->id,
            'sender' => $user,
            'sender_name' => $user->name,
            'message' => $request->message,
            'reply_to' => $request->reply_to,
            'created_at' => now(),
        ];

        // Save message to cache
        $this->saveMessageToCache($chatId, $message);

        // Update chat list cache
        $this->updateChatListCache($chatData['buyer_id'], $chatData['seller_id'], $chatData['car_id'], $message);

        // Broadcast reply notification
        try {
            event(new \App\Events\MessageReplied($message, $chat, $repliedToMessage));
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast reply notification', [
                'error' => $e->getMessage(),
            ]);
        }

        // Also broadcast as regular message
        try {
            event(new \App\Events\MessageSent($message, $chat));
            
            // Broadcast notification to recipient (not sender)
            $recipientId = $chatData['buyer_id'] == $user->id ? $chatData['seller_id'] : $chatData['buyer_id'];
            event(new \App\Events\NewChatMessage($message, $chat, $recipientId));
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast message', [
                'error' => $e->getMessage(),
            ]);
        }

        // Get replied to message data safely
        $repliedToSenderName = 'User';
        if (isset($repliedToMessage->sender_name)) {
            $repliedToSenderName = $repliedToMessage->sender_name;
        } elseif (isset($repliedToMessage->sender) && is_object($repliedToMessage->sender)) {
            $repliedToSenderName = $repliedToMessage->sender->name ?? 'User';
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'chat_id' => $message->chat_id,
                'sender_id' => $message->sender_id,
                'sender_name' => $user->name,
                'message' => $message->message,
                'reply_to' => $message->reply_to,
                'replied_to_message' => [
                    'id' => $repliedToMessage->id ?? null,
                    'sender_name' => $repliedToSenderName,
                    'message' => $repliedToMessage->message ?? '',
                ],
                'created_at' => $message->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Edit a message
     */
    public function edit(Request $request, $chatId, $messageId)
    {
        $user = Auth::user();
        
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Get messages from cache
        $messages = $this->getChatMessages($chatId);
        $messageToEdit = $messages->firstWhere('id', $messageId);
        
        if (!$messageToEdit) {
            return response()->json(['error' => 'Message tidak ditemukan'], 404);
        }

        // Check if user is the sender
        if ($messageToEdit->sender_id != $user->id) {
            return response()->json(['error' => 'Anda hanya bisa mengedit pesan Anda sendiri'], 403);
        }

        // Check if message has been read by recipient
        $recipientId = $chatData['buyer_id'] == $user->id ? $chatData['seller_id'] : $chatData['buyer_id'];
        $isRead = false;
        
        // Check if recipient has read the message (check in cache)
        $cacheKey = 'chat_messages_' . $chatId;
        $allMessages = Cache::get($cacheKey, []);
        foreach ($allMessages as $msg) {
            if ($msg['id'] == $messageId) {
                $readBy = is_array($msg['read_by'] ?? null) ? $msg['read_by'] : [];
                // Only check if recipient ID is in read_by array
                if (in_array($recipientId, $readBy)) {
                    $isRead = true;
                }
                break;
            }
        }
        
        if ($isRead) {
            return response()->json(['error' => 'Pesan yang sudah dibaca tidak bisa di-edit'], 403);
        }

        // Update message in cache
        $cacheKey = 'chat_messages_' . $chatId;
        $allMessages = Cache::get($cacheKey, []);
        
        foreach ($allMessages as $key => $msg) {
            if ($msg['id'] == $messageId) {
                $allMessages[$key]['message'] = $request->message;
                $allMessages[$key]['edited_at'] = now()->toIso8601String();
                break;
            }
        }
        
        Cache::put($cacheKey, $allMessages, now()->addDays(7));

        // Broadcast edit event
        $chat = (object)[
            'id' => $chatId,
            'buyer_id' => $chatData['buyer_id'],
            'seller_id' => $chatData['seller_id'],
            'car_id' => $chatData['car_id'],
        ];

        try {
            event(new \App\Events\MessageSent((object)[
                'id' => $messageId,
                'chat_id' => $chatId,
                'sender_id' => $user->id,
                'sender_name' => $user->name,
                'message' => $request->message,
                'edited_at' => now(),
                'created_at' => $messageToEdit->created_at ?? now(),
            ], $chat));
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast edit', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $messageId,
                'message' => $request->message,
                'edited_at' => now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * Delete a message
     */
    public function delete($chatId, $messageId)
    {
        $user = Auth::user();
        
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get messages from cache
        $messages = $this->getChatMessages($chatId);
        $messageToDelete = $messages->firstWhere('id', $messageId);
        
        if (!$messageToDelete) {
            return response()->json(['error' => 'Message tidak ditemukan'], 404);
        }

        // Update message in cache (soft delete)
        $cacheKey = 'chat_messages_' . $chatId;
        $allMessages = Cache::get($cacheKey, []);
        
        foreach ($allMessages as $key => $msg) {
            if ($msg['id'] == $messageId) {
                // If sender, mark as deleted for sender only
                if ($messageToDelete->sender_id == $user->id) {
                    $allMessages[$key]['is_deleted_for_sender'] = true;
                } else {
                    // If not sender, mark as deleted (for everyone)
                    $allMessages[$key]['deleted_at'] = now()->toIso8601String();
                }
                break;
            }
        }
        
        Cache::put($cacheKey, $allMessages, now()->addDays(7));

        // Broadcast delete event
        $chat = (object)[
            'id' => $chatId,
            'buyer_id' => $chatData['buyer_id'],
            'seller_id' => $chatData['seller_id'],
            'car_id' => $chatData['car_id'],
        ];

        try {
            event(new \App\Events\MessageSent((object)[
                'id' => $messageId,
                'chat_id' => $chatId,
                'sender_id' => $messageToDelete->sender_id,
                'sender_name' => $messageToDelete->sender_name ?? 'User',
                'message' => $messageToDelete->message,
                'deleted_at' => now(),
                'is_deleted_for_sender' => $messageToDelete->sender_id == $user->id,
                'created_at' => $messageToDelete->created_at ?? now(),
            ], $chat));
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast delete', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dihapus',
        ]);
    }
}

