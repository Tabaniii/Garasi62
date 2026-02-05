<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\car;
use App\Models\Chat;
use App\Models\Message;
use App\Events\MessageUpdated;
use App\Events\MessageDeleted;
use App\Events\MessageRead;
use App\Events\TypingIndicator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
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
        if (!$carId || !$car) {
            return redirect()->back()->with('error', 'Pilih mobil terlebih dahulu untuk memulai chat.');
        }

        // Generate chat ID from combination
        $chatId = $this->generateChatId($buyer->id, $seller->id, $carId);
        
        // Try to get chat from database first
        $dbChat = Chat::where('buyer_id', $buyer->id)
            ->where('seller_id', $seller->id)
            ->where('car_id', $carId)
            ->first();
        
        // Create virtual chat object
        $chat = (object)[
            'id' => $chatId,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'car_id' => $carId,
        ];

        if ($dbChat) {
            $this->markMessagesAsRead($chatId, $dbChat->id, $buyer->id);

            $messages = $dbChat->messages()
                ->with('sender', 'replyTo.sender')
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($msg) use ($chatId) {
                    return (object)[
                        'id' => $msg->id,
                        'chat_id' => $chatId,
                        'sender_id' => $msg->sender_id,
                        'sender_name' => $msg->sender->name ?? 'User',
                        'message' => $msg->is_deleted ? 'Pesan ini dihapus' : $msg->message,
                        'is_deleted' => $msg->is_deleted ?? false,
                        'is_read' => $msg->is_read,
                        'reply_to_message_id' => $msg->reply_to_message_id,
                        'reply_to_message' => $msg->replyTo ? (object)[
                            'id' => $msg->replyTo->id,
                            'message' => $msg->replyTo->is_deleted ? 'Pesan ini dihapus' : $msg->replyTo->message,
                            'is_deleted' => $msg->replyTo->is_deleted ?? false,
                            'sender_name' => $msg->replyTo->sender->name ?? 'User',
                        ] : null,
                        'created_at' => $msg->created_at,
                    ];
                });
        } else {
            $messages = $this->getChatMessages($chatId);
        }

        $otherUser = $seller;
        $user = $buyer;

        $pusherConfig = [
            'key' => config('broadcasting.connections.pusher.key'),
            'cluster' => config('broadcasting.connections.pusher.options.cluster', 'ap1'),
        ];

        return view('chat.show', compact('chat', 'otherUser', 'user', 'car', 'messages', 'pusherConfig'));
    }

    public function updateMessage(Request $request, $chatId, $messageId)
    {
        $user = Auth::user();
        
        $chatId = urldecode($chatId);
        
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

        try {
            $message = Message::findOrFail($messageId);

            if ($message->sender_id != $user->id) {
                return response()->json(['error' => 'Unauthorized to edit this message'], 403);
            }

            if ($message->is_deleted) {
                return response()->json(['error' => 'Pesan sudah dihapus, tidak dapat diedit'], 403);
            }

            if ($message->is_read) {
                return response()->json(['error' => 'Pesan sudah dibaca, tidak dapat diedit'], 403);
            }

            $message->update([
                'message' => $request->message
            ]);

            $this->updateMessageInCache($chatId, $message);

            event(new MessageUpdated($chatId, $message));
            
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to update message', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengupdate pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMessage($chatId, $messageId)
    {
        $user = Auth::user();
        
        $chatId = urldecode($chatId);
        
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $message = Message::findOrFail($messageId);

            if ($message->sender_id != $user->id) {
                return response()->json(['error' => 'Unauthorized to delete this message'], 403);
            }

            if (!$message->is_deleted) {
                $message->update([
                    'message' => '',
                    'is_deleted' => true,
                ]);
            }

            $this->updateMessageInCache($chatId, $message);
            $this->refreshChatLastMessageFromCache($chatId, $chatData);

            event(new MessageDeleted($chatId, $messageId));
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dihapus',
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to delete message', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Gagal menghapus pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function markMessagesAsRead($chatId, $dbChatId, $readerId)
    {
        $messageIds = Message::where('chat_id', $dbChatId)
            ->where('sender_id', '!=', $readerId)
            ->where('is_read', false)
            ->pluck('id')
            ->all();

        if (empty($messageIds)) {
            return;
        }

        $affected = Message::whereIn('id', $messageIds)
            ->update(['is_read' => true]);
            
        if ($affected > 0) {
            $cacheKey = 'chat_messages_' . $chatId;
            $messages = Cache::get($cacheKey, []);
            $cacheUpdated = false;
            
            foreach ($messages as &$msg) {
                if (isset($msg['sender_id']) && $msg['sender_id'] != $readerId) {
                    if (!isset($msg['is_read']) || $msg['is_read'] === false) {
                        $msg['is_read'] = true;
                        $cacheUpdated = true;
                    }
                }
            }
            
            if ($cacheUpdated) {
                Cache::put($cacheKey, $messages, now()->addDays(7));
            }

            event(new MessageRead($chatId, $readerId, $messageIds));
        }
    }

    private function updateMessageInCache($chatId, $updatedMessage)
    {
        $cacheKey = 'chat_messages_' . $chatId;
        $messages = Cache::get($cacheKey, []);
        
        foreach ($messages as &$msg) {
            if (isset($msg['id']) && $msg['id'] == $updatedMessage->id) {
                $msg['message'] = $updatedMessage->message;
                if (isset($updatedMessage->is_deleted)) {
                    $msg['is_deleted'] = (bool)$updatedMessage->is_deleted;
                }
                break;
            }
        }
        
        Cache::put($cacheKey, $messages, now()->addDays(7));
    }

    private function refreshChatLastMessageFromCache($chatId, $chatData)
    {
        $cacheKey = 'chat_messages_' . $chatId;
        $messages = Cache::get($cacheKey, []);
        if (empty($messages)) {
            return;
        }

        $lastMessage = end($messages);
        if (!$lastMessage || !isset($lastMessage['message'])) {
            return;
        }

        $lastMessageText = $lastMessage['message'];
        if (!empty($lastMessage['is_deleted'])) {
            $lastMessageText = 'Pesan ini dihapus';
        }

        $createdAt = $lastMessage['created_at'] ?? now()->toIso8601String();

        $chatIdValue = $chatId;
        $buyerId = $chatData['buyer_id'];
        $sellerId = $chatData['seller_id'];
        $carId = $chatData['car_id'];

        $buyerChatsKey = 'user_chats_' . $buyerId;
        $buyerChats = Cache::get($buyerChatsKey, []);
        if (isset($buyerChats[$chatIdValue])) {
            $buyerChats[$chatIdValue]['last_message'] = $lastMessageText;
            $buyerChats[$chatIdValue]['last_message_at'] = $createdAt;
            Cache::put($buyerChatsKey, $buyerChats, now()->addDays(7));
        }

        $sellerChatsKey = 'user_chats_' . $sellerId;
        $sellerChats = Cache::get($sellerChatsKey, []);
        if (isset($sellerChats[$chatIdValue])) {
            $sellerChats[$chatIdValue]['last_message'] = $lastMessageText;
            $sellerChats[$chatIdValue]['last_message_at'] = $createdAt;
            Cache::put($sellerChatsKey, $sellerChats, now()->addDays(7));
        }
    }

    private function removeMessageFromCache($chatId, $messageId)
    {
        $cacheKey = 'chat_messages_' . $chatId;
        $messages = Cache::get($cacheKey, []);
        
        $messages = array_filter($messages, function($msg) use ($messageId) {
            return isset($msg['id']) && $msg['id'] != $messageId;
        });
        
        Cache::put($cacheKey, array_values($messages), now()->addDays(7));
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

        // Try to get chat from database first
        $dbChat = Chat::where('buyer_id', $chatData['buyer_id'])
            ->where('seller_id', $chatData['seller_id'])
            ->where('car_id', $chatData['car_id'])
            ->first();
        
        // Create virtual chat object
        $chat = (object)[
            'id' => $chatId,
            'buyer_id' => $chatData['buyer_id'],
            'seller_id' => $chatData['seller_id'],
            'car_id' => $chatData['car_id'],
        ];

        if ($dbChat) {
            $this->markMessagesAsRead($chatId, $dbChat->id, $user->id);

            $messages = $dbChat->messages()
                ->with('sender', 'replyTo.sender')
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($msg) use ($chatId) {
                    return (object)[
                        'id' => $msg->id,
                        'chat_id' => $chatId,
                        'sender_id' => $msg->sender_id,
                        'sender_name' => $msg->sender->name ?? 'User',
                        'message' => $msg->message,
                        'is_read' => $msg->is_read,
                        'reply_to_message_id' => $msg->reply_to_message_id,
                        'reply_to_message' => $msg->replyTo ? (object)[
                            'id' => $msg->replyTo->id,
                            'message' => $msg->replyTo->message,
                            'sender_name' => $msg->replyTo->sender->name ?? 'User',
                        ] : null,
                        'created_at' => $msg->created_at,
                    ];
                });
        } else {
            $messages = $this->getChatMessages($chatId);
        }

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

    public function markAsRead(Request $request, $chatId)
    {
        $user = Auth::user();
        $chatId = urldecode($chatId);
        $chatData = $this->parseChatId($chatId);

        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $dbChat = Chat::where('buyer_id', $chatData['buyer_id'])
            ->where('seller_id', $chatData['seller_id'])
            ->where('car_id', $chatData['car_id'])
            ->first();

        if ($dbChat) {
            $this->markMessagesAsRead($chatId, $dbChat->id, $user->id);
        }

        $cacheKey = 'user_chats_' . $user->id;
        $chatsData = Cache::get($cacheKey, []);
        if (isset($chatsData[$chatId])) {
            $chatsData[$chatId]['unread_count'] = 0;
            Cache::put($cacheKey, $chatsData, now()->addDays(7));
        }

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        $cacheKey = 'user_chats_' . $user->id;
        $chatsData = Cache::get($cacheKey, []);
        $total = 0;
        $perChat = [];

        foreach ($chatsData as $chatId => $chatData) {
            $count = (int)($chatData['unread_count'] ?? 0);
            $lastMessage = $chatData['last_message'] ?? '';
            $lastMessageAt = $chatData['last_message_at'] ?? null;
            $lastMessageTime = null;
            if ($lastMessageAt) {
                try {
                    $lastMessageTime = \Carbon\Carbon::parse($lastMessageAt)->diffForHumans();
                } catch (\Exception $e) {
                    $lastMessageTime = null;
                }
            }
            if ($count > 0) {
                $total += $count;
            }
            $perChat[$chatId] = [
                'unread_count' => $count,
                'last_message' => $lastMessage,
                'last_message_at' => $lastMessageAt,
                'last_message_time' => $lastMessageTime,
            ];
        }

        return response()->json([
            'total' => $total,
            'per_chat' => $perChat,
        ]);
    }

    /**
     * Store a new message (save to cache, broadcast real-time)
     */
    public function store(Request $request, $chatId)
    {
        $user = Auth::user();
        
        // Decode chat ID if it's URL encoded
        $chatId = urldecode($chatId);
        
        // Parse chat ID to get buyer_id, seller_id, car_id
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            \Log::error('Invalid chat ID', [
                'chatId' => $chatId,
                'userId' => $user->id,
                'userRole' => $user->role
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Invalid chat ID: ' . $chatId
            ], 400);
        }

        // Verify user is part of this chat
        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'reply_to_message_id' => 'nullable|integer',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Pesan tidak valid. Pastikan pesan tidak kosong dan maksimal 1000 karakter.',
                'errors' => $e->errors()
            ], 422);
        }

        // Get or create chat in database
        $dbChat = Chat::firstOrCreate(
            [
                'buyer_id' => $chatData['buyer_id'],
                'seller_id' => $chatData['seller_id'],
                'car_id' => $chatData['car_id'],
            ],
            [
                'last_message_at' => now(),
            ]
        );
        
        // Update last_message_at
        $dbChat->update(['last_message_at' => now()]);

        $recentCount = Message::where('chat_id', $dbChat->id)
            ->where('sender_id', $user->id)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->count();

        if ($recentCount >= 5) {
            return response()->json([
                'success' => false,
                'error' => 'Terlalu banyak pesan dalam waktu singkat. Coba lagi sebentar.'
            ], 429);
        }

        $lastMessage = Message::where('chat_id', $dbChat->id)
            ->where('sender_id', $user->id)
            ->latest()
            ->first();

        $replyMessageId = $request->input('reply_to_message_id');
        $replyMessage = null;
        if ($replyMessageId) {
            $replyMessage = Message::with('sender')
                ->where('id', $replyMessageId)
                ->where('chat_id', $dbChat->id)
                ->first();

            if (!$replyMessage) {
                return response()->json([
                    'success' => false,
                    'error' => 'Pesan yang direply tidak ditemukan',
                ], 422);
            }
        }

        if ($lastMessage && $lastMessage->message === $request->message) {
            $recentDuplicate = $lastMessage->created_at >= now()->subSeconds(3) &&
                ((int)($lastMessage->reply_to_message_id ?? 0) === (int)($replyMessageId ?? 0));

            if ($recentDuplicate) {
                $lastMessage->loadMissing('sender', 'replyTo.sender');
                return response()->json([
                    'success' => true,
                    'message' => [
                        'id' => $lastMessage->id,
                        'chat_id' => $chatId,
                        'db_chat_id' => $dbChat->id,
                        'sender_id' => $lastMessage->sender_id,
                        'sender_name' => $lastMessage->sender->name ?? $user->name,
                        'sender' => [
                            'id' => $user->id,
                            'name' => $lastMessage->sender->name ?? $user->name,
                        ],
                        'message' => $lastMessage->is_deleted ? 'Pesan ini dihapus' : $lastMessage->message,
                        'is_deleted' => $lastMessage->is_deleted ?? false,
                        'is_read' => $lastMessage->is_read ?? false,
                        'reply_to_message_id' => $lastMessage->reply_to_message_id,
                        'reply_to_message' => $lastMessage->replyTo ? [
                            'id' => $lastMessage->replyTo->id,
                            'message' => $lastMessage->replyTo->is_deleted ? 'Pesan ini dihapus' : $lastMessage->replyTo->message,
                            'is_deleted' => $lastMessage->replyTo->is_deleted ?? false,
                            'sender_name' => $lastMessage->replyTo->sender->name ?? 'User',
                        ] : null,
                        'created_at' => $lastMessage->created_at->toIso8601String(),
                    ],
                ]);
            }
        }
        
        // Create message in database
        $dbMessage = Message::create([
            'chat_id' => $dbChat->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
            'reply_to_message_id' => $replyMessageId,
        ]);
        
        // Load sender relationship
        $dbMessage->load('sender');
        
        // Create virtual chat object for cache compatibility
        $chat = (object)[
            'id' => $chatId,
            'db_id' => $dbChat->id,
            'buyer_id' => $chatData['buyer_id'],
            'seller_id' => $chatData['seller_id'],
            'car_id' => $chatData['car_id'],
        ];

        // Create message object for cache (using database ID)
        $message = (object)[
            'id' => $dbMessage->id,
            'chat_id' => $chatId,
            'db_chat_id' => $dbChat->id,
            'sender_id' => $user->id,
            'sender' => $user,
            'sender_name' => $user->name,
            'message' => $request->message,
            'is_deleted' => false,
            'is_read' => false,
            'reply_to_message_id' => $replyMessageId,
            'reply_to_message' => $replyMessage ? (object)[
                'id' => $replyMessage->id,
                'message' => $replyMessage->is_deleted ? 'Pesan ini dihapus' : $replyMessage->message,
                'is_deleted' => $replyMessage->is_deleted ?? false,
                'sender_name' => $replyMessage->sender->name ?? 'User',
            ] : null,
            'created_at' => $dbMessage->created_at,
        ];
        
        \Log::info('Saving message to database and cache', [
            'chat_id' => $chatId,
            'db_chat_id' => $dbChat->id,
            'message_id' => $dbMessage->id,
            'sender_id' => $user->id,
            'sender_name' => $user->name,
        ]);

        // Save message to cache (for real-time performance)
        $this->saveMessageToCache($chatId, $message);
        
        // Also save to cache using database chat ID for compatibility
        $this->saveMessageToCache('chat_db_' . $dbChat->id, $message);

        // Update chat list cache
        $this->updateChatListCache($chatData['buyer_id'], $chatData['seller_id'], $chatData['car_id'], $message);

        // Broadcast the message to both participants (real-time)
        try {
            \Log::info('Broadcasting message event', [
                'chat_id' => $chatId,
                'db_chat_id' => $dbChat->id,
                'message_id' => $dbMessage->id,
                'sender_id' => $user->id,
            ]);
            
            event(new \App\Events\MessageSent($message, $chat));
            
            \Log::info('Message broadcasted successfully', [
                'chat_id' => $chatId,
                'db_chat_id' => $dbChat->id,
                'message_id' => $dbMessage->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast message', [
                'chat_id' => $chatId,
                'db_chat_id' => $dbChat->id,
                'message_id' => $dbMessage->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Continue anyway - message is saved in database and cache
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $dbMessage->id,
                'chat_id' => $message->chat_id,
                'db_chat_id' => $dbChat->id,
                'sender_id' => $message->sender_id,
                'sender_name' => $user->name,
                'sender' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'message' => $message->message,
                'is_deleted' => false,
                'is_read' => false,
                'reply_to_message_id' => $replyMessageId,
                'reply_to_message' => $replyMessage ? [
                    'id' => $replyMessage->id,
                    'message' => $replyMessage->is_deleted ? 'Pesan ini dihapus' : $replyMessage->message,
                    'is_deleted' => $replyMessage->is_deleted ?? false,
                    'sender_name' => $replyMessage->sender->name ?? 'User',
                ] : null,
                'created_at' => $dbMessage->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Get messages for a chat (AJAX)
     * Load from cache (not database)
     */
    public function getMessages(Request $request, $chatId)
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

        // Try to get chat from database to mark as read
        $dbChat = Chat::where('buyer_id', $chatData['buyer_id'])
            ->where('seller_id', $chatData['seller_id'])
            ->where('car_id', $chatData['car_id'])
            ->first();

        if ($dbChat) {
            $this->markMessagesAsRead($chatId, $dbChat->id, $user->id);
        }

        $source = $request->query('source', 'cache');
        if ($source === 'db') {
            usleep(400000);
            $page = max(1, (int)$request->query('page', 1));
            $perPage = min(50, max(10, (int)$request->query('per_page', 30)));
            $beforeId = (int)$request->query('before_id', 0);

            if (!$dbChat) {
                return response()->json(['messages' => []]);
            }

            $query = Message::where('chat_id', $dbChat->id)->with('sender', 'replyTo.sender');
            if ($beforeId > 0) {
                $query->where('id', '<', $beforeId)->orderBy('id', 'desc');
            } else {
                $query->orderBy('created_at', 'asc')->skip(($page - 1) * $perPage);
            }

            $messages = $query
                ->take($perPage)
                ->get();

            if ($beforeId > 0) {
                $messages = $messages->sortBy('id')->values();
            }

            $messages = $messages->map(function($msg) use ($chatId) {
                    return [
                        'id' => $msg->id,
                        'chat_id' => $chatId,
                        'sender_id' => $msg->sender_id,
                        'sender_name' => $msg->sender->name ?? 'User',
                        'message' => $msg->is_deleted ? 'Pesan ini dihapus' : $msg->message,
                        'is_deleted' => $msg->is_deleted ?? false,
                        'is_read' => $msg->is_read,
                        'reply_to_message_id' => $msg->reply_to_message_id,
                        'reply_to_message' => $msg->replyTo ? [
                            'id' => $msg->replyTo->id,
                            'message' => $msg->replyTo->is_deleted ? 'Pesan ini dihapus' : $msg->replyTo->message,
                            'is_deleted' => $msg->replyTo->is_deleted ?? false,
                            'sender_name' => $msg->replyTo->sender->name ?? 'User',
                        ] : null,
                        'created_at' => $msg->created_at->toIso8601String(),
                    ];
                })
                ->values()
                ->all();

            return response()->json([
                'messages' => $messages,
                'source' => 'db',
            ]);
        }

        // Load messages from cache (now updated)
        $messages = $this->getChatMessages($chatId);

        $formattedMessages = $messages->map(function($msg) {
            $isDeleted = $msg->is_deleted ?? (($msg->message ?? '') === '');
            return [
                'id' => $msg->id ?? null,
                'chat_id' => $msg->chat_id ?? null,
                'sender_id' => $msg->sender_id ?? null,
                'sender_name' => $msg->sender_name ?? 'User',
                'message' => $msg->message ?? '',
                'is_deleted' => $isDeleted,
                'is_read' => $msg->is_read ?? false,
                'reply_to_message_id' => $msg->reply_to_message_id ?? null,
                'reply_to_message' => $msg->reply_to_message ?? null,
                'created_at' => isset($msg->created_at) ? 
                    (is_string($msg->created_at) ? $msg->created_at : 
                     (is_object($msg->created_at) ? $msg->created_at->toIso8601String() : now()->toIso8601String())) : 
                    now()->toIso8601String(),
            ];
        })->values()->all();

        return response()->json([
            'messages' => $formattedMessages,
            'source' => 'cache',
        ]);
    }

    public function typing(Request $request, $chatId)
    {
        $user = Auth::user();
        $chatId = urldecode($chatId);
        $chatData = $this->parseChatId($chatId);

        if (!$chatData) {
            return response()->json(['error' => 'Invalid chat ID'], 400);
        }

        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'typing' => 'required|boolean',
        ]);

        broadcast(new TypingIndicator($chatId, $user->id, (bool)$request->typing))->toOthers();

        return response()->json(['success' => true]);
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
        
        // Add new message with proper format
        $newMessage = [
            'id' => $message->id,
            'chat_id' => $message->chat_id,
            'sender_id' => $message->sender_id,
            'sender_name' => $senderName,
            'message' => $message->message,
            'is_deleted' => $message->is_deleted ?? false,
            'is_read' => $message->is_read ?? false,
            'reply_to_message_id' => $message->reply_to_message_id ?? null,
            'reply_to_message' => $message->reply_to_message ?? null,
            'created_at' => $createdAt->toIso8601String(),
        ];
        
        // Check if message already exists (prevent duplicates)
        $exists = false;
        foreach ($messages as $existingMsg) {
            if (isset($existingMsg['id']) && $existingMsg['id'] === $message->id) {
                $exists = true;
                break;
            }
        }
        
        if (!$exists) {
            $messages[] = $newMessage;
        }
        
        // Dynamic cache cap: keep ~30% of history (min 30, max 300)
        $cap = 100;
        $chatData = $this->parseChatId($chatId);
        if ($chatData) {
            $dbChat = Chat::where('buyer_id', $chatData['buyer_id'])
                ->where('seller_id', $chatData['seller_id'])
                ->where('car_id', $chatData['car_id'])
                ->first();
            if ($dbChat) {
                $dbCount = Message::where('chat_id', $dbChat->id)->count();
                $cap = max(30, min((int)ceil($dbCount * 0.3), 300));
            } else {
                $cap = max(30, min((int)ceil(count($messages) * 0.3), 300));
            }
        } else {
            $cap = max(30, min((int)ceil(count($messages) * 0.3), 300));
        }

        if (count($messages) > $cap) {
            $messages = array_slice($messages, -$cap);
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
            'last_message' => $message->is_deleted ? 'Pesan ini dihapus' : $message->message,
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
            'last_message' => $message->is_deleted ? 'Pesan ini dihapus' : $message->message,
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
     * Only return chats that have actual messages
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
        
        $validChats = [];
        $invalidChatIds = [];
        $cacheUpdated = false;
        
        $chats = collect($chatsData)->map(function($chatCacheData, $chatId) use ($role, $userId, &$validChats, &$invalidChatIds, &$chatsData, &$cacheUpdated) {
            // Parse chat ID to get buyer_id, seller_id, car_id
            $chatData = $this->parseChatId($chatId);
            if (!$chatData) {
                \Log::warning('Failed to parse chat ID', ['chatId' => $chatId]);
                $invalidChatIds[] = $chatId;
                return null;
            }
            
            // Get other user based on role
            // IMPORTANT: For buyer, we want to show seller. For seller, we want to show buyer.
            $otherUserId = ($chatData['buyer_id'] == $userId) ? $chatData['seller_id'] : $chatData['buyer_id'];
            $otherUser = User::find($otherUserId);
            
            // Double check: make sure otherUser is not the current user
            if ($otherUser && $otherUser->id == $userId) {
                // This shouldn't happen, but handle it
                \Log::warning('Other user is same as current user', [
                    'chatId' => $chatId,
                    'userId' => $userId,
                    'role' => $role,
                    'buyer_id' => $chatData['buyer_id'],
                    'seller_id' => $chatData['seller_id'],
                    'otherUserId' => $otherUserId,
                ]);
                $invalidChatIds[] = $chatId;
                return null;
            }
            
            if (!$otherUser) {
                \Log::warning('Other user not found', [
                    'role' => $role,
                    'userId' => $userId,
                    'buyer_id' => $chatData['buyer_id'],
                    'seller_id' => $chatData['seller_id'],
                    'otherUserId' => $otherUserId,
                ]);
                $invalidChatIds[] = $chatId;
                return null;
            }
            
            $car = $chatData['car_id'] ? car::find($chatData['car_id']) : null;
            
            // Get last message from cache data
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
            
            // IMPORTANT: Only include chats that have actual messages
            // Check if there are messages in cache for this chat
            $messagesCacheKey = 'chat_messages_' . $chatId;
            $messages = Cache::get($messagesCacheKey, []);
            
            // If no messages exist and no last_message, skip this chat
            if (empty($messages) && empty($lastMessage)) {
                $invalidChatIds[] = $chatId;
                return null;
            }
            
            if (!empty($messages)) {
                $lastFromCache = end($messages);
                $lastFromCacheText = $lastFromCache['message'] ?? '';
                if (!empty($lastFromCache['is_deleted'])) {
                    $lastFromCacheText = 'Pesan ini dihapus';
                }
                $lastFromCacheAt = $lastFromCache['created_at'] ?? null;
                if ($lastFromCacheText !== '' && $lastFromCacheAt) {
                    try {
                        $lastMessageAt = \Carbon\Carbon::parse($lastFromCacheAt);
                        $lastMessage = $lastFromCacheText;
                        if (($chatCacheData['last_message'] ?? null) !== $lastFromCacheText || ($chatCacheData['last_message_at'] ?? null) !== $lastFromCacheAt) {
                            $chatsData[$chatId]['last_message'] = $lastFromCacheText;
                            $chatsData[$chatId]['last_message_at'] = $lastFromCacheAt;
                            $cacheUpdated = true;
                        }
                    } catch (\Exception $e) {
                    }
                }
            }

            if (empty($lastMessage)) {
                $invalidChatIds[] = $chatId;
                return null;
            }
            
            $validChats[] = $chatId;
            
            // Get buyer and seller objects for proper access
            $buyerObj = User::find($chatData['buyer_id']);
            $sellerObj = User::find($chatData['seller_id']);
            
            return (object)[
                'id' => $chatId,
                'buyer_id' => $chatData['buyer_id'],
                'seller_id' => $chatData['seller_id'],
                'car_id' => $chatData['car_id'],
                'buyer' => $buyerObj, // Always set buyer object
                'seller' => $sellerObj, // Always set seller object
                'other_user' => $otherUser, // The user we're chatting with (seller for buyer, buyer for seller)
                'other_user_id' => $otherUser->id, // Other user ID for reference
                'car' => $car,
                'last_message' => (object)[
                    'message' => $lastMessage,
                    'created_at' => $lastMessageAt,
                ],
                'unread_count' => $unreadCount,
            ];
        })->filter(function($chat) {
            // Filter out null chats (invalid or no messages)
            return $chat !== null;
        })->sortByDesc(function($chat) {
            return $chat->last_message->created_at;
        })->values();
        
        if ($cacheUpdated) {
            Cache::put($cacheKey, $chatsData, now()->addDays(7));
        }

        // Clean up invalid chats from cache
        if (!empty($invalidChatIds)) {
            $this->cleanupInvalidChats($userId, $invalidChatIds);
        }
        
        return $chats;
    }
    
    /**
     * Clean up invalid chats from cache
     */
    private function cleanupInvalidChats($userId, $invalidChatIds)
    {
        $cacheKey = 'user_chats_' . $userId;
        $chatsData = Cache::get($cacheKey, []);
        
        foreach ($invalidChatIds as $chatId) {
            if (isset($chatsData[$chatId])) {
                unset($chatsData[$chatId]);
            }
        }
        
        Cache::put($cacheKey, $chatsData, now()->addDays(7));
    }
    
    /**
     * Delete single chat
     */
    public function destroySingle($chatId)
    {
        $user = Auth::user();
        
        // Decode chat ID if it's URL encoded
        $chatId = urldecode($chatId);
        
        // Parse chat ID to get buyer_id, seller_id, car_id
        $chatData = $this->parseChatId($chatId);
        
        if (!$chatData) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid chat ID'
            ], 400);
        }
        
        // Verify user is part of this chat
        if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }
        
        return $this->deleteChat($chatId, $chatData, $user->id);
    }
    
    /**
     * Delete multiple chats
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'chat_ids' => 'required|array',
            'chat_ids.*' => 'required|string',
        ]);
        
        $chatIds = $request->chat_ids;
        $deletedCount = 0;
        $errors = [];
        
        foreach ($chatIds as $chatId) {
            // Decode chat ID if it's URL encoded
            $chatId = urldecode($chatId);
            
            // Parse chat ID to get buyer_id, seller_id, car_id
            $chatData = $this->parseChatId($chatId);
            
            if (!$chatData) {
                $errors[] = "Invalid chat ID: {$chatId}";
                continue;
            }
            
            // Verify user is part of this chat
            if ($chatData['buyer_id'] != $user->id && $chatData['seller_id'] != $user->id) {
                $errors[] = "Unauthorized for chat: {$chatId}";
                continue;
            }
            
            $result = $this->deleteChat($chatId, $chatData, $user->id);
            
            if ($result->getData()->success ?? false) {
                $deletedCount++;
            } else {
                $errors[] = "Failed to delete chat: {$chatId}";
            }
        }
        
        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
            'errors' => $errors,
            'message' => "Berhasil menghapus {$deletedCount} obrolan"
        ]);
    }
    
    /**
     * Delete chat from database and cache
     */
    private function deleteChat($chatId, $chatData, $userId)
    {
        try {
            // Delete from database
            $dbChat = Chat::where('buyer_id', $chatData['buyer_id'])
                ->where('seller_id', $chatData['seller_id'])
                ->where('car_id', $chatData['car_id'])
                ->first();
            
            if ($dbChat) {
                // Delete all messages
                Message::where('chat_id', $dbChat->id)->delete();
                // Delete chat
                $dbChat->delete();
            }
            
            // Delete from cache for buyer
            $buyerChatsKey = 'user_chats_' . $chatData['buyer_id'];
            $buyerChats = Cache::get($buyerChatsKey, []);
            if (isset($buyerChats[$chatId])) {
                unset($buyerChats[$chatId]);
                Cache::put($buyerChatsKey, $buyerChats, now()->addDays(7));
            }
            
            // Delete from cache for seller
            $sellerChatsKey = 'user_chats_' . $chatData['seller_id'];
            $sellerChats = Cache::get($sellerChatsKey, []);
            if (isset($sellerChats[$chatId])) {
                unset($sellerChats[$chatId]);
                Cache::put($sellerChatsKey, $sellerChats, now()->addDays(7));
            }
            
            // Delete messages from cache
            $messagesCacheKey = 'chat_messages_' . $chatId;
            Cache::forget($messagesCacheKey);
            
            // Also delete using database chat ID if exists
            if ($dbChat) {
                $messagesCacheKeyDb = 'chat_messages_chat_db_' . $dbChat->id;
                Cache::forget($messagesCacheKeyDb);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Obrolan berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete chat', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Gagal menghapus obrolan: ' . $e->getMessage()
            ], 500);
        }
    }
}
