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
            return redirect()->route('dashboard')->with('error', 'Chat tidak ditemukan.');
        }

        // Verify user is part of this chat
        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
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
        
        // Add new message
        $messages[] = [
            'id' => $message->id,
            'chat_id' => $message->chat_id,
            'sender_id' => $message->sender_id,
            'sender_name' => $senderName,
            'message' => $message->message,
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
}

