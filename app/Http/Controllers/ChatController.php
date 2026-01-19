<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\car;
use Illuminate\Support\Facades\Auth;

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

        // Find or create chat
        $chat = Chat::firstOrCreate(
            [
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'car_id' => $carId,
            ],
            [
                'last_message_at' => now(),
            ]
        );

        // Mark messages as read
        Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $buyer->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()->with('sender')->get();

        // Set otherUser for consistency
        $otherUser = $seller;
        $user = $buyer;

        return view('chat.show', compact('chat', 'otherUser', 'user', 'car', 'messages'));
    }

    /**
     * Show chat by chat ID (for both buyer and seller)
     */
    public function show($chatId)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($chatId);

        // Verify user is part of this chat
        if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke chat ini.');
        }

        // Determine the other user
        $otherUser = $chat->buyer_id === $user->id ? $chat->seller : $chat->buyer;
        $car = $chat->car;

        // Mark messages as read
        Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()->with('sender')->get();

        return view('chat.show', compact('chat', 'otherUser', 'user', 'car', 'messages'));
    }

    /**
     * Get list of chats for buyer
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Hanya buyer yang dapat mengakses chat.');
        }

        $chats = Chat::where('buyer_id', $user->id)
            ->with(['seller', 'car'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($chat) {
                // Query langsung untuk mendapatkan pesan terbaru berdasarkan id (auto increment)
                $chat->last_message = Message::where('chat_id', $chat->id)
                    ->orderBy('id', 'desc')
                    ->first();
                return $chat;
            });

        return view('chat.index', compact('chats'));
    }

    /**
     * Get list of chats for seller
     */
    public function sellerIndex()
    {
        $user = Auth::user();

        if ($user->role !== 'seller') {
            return redirect()->route('dashboard')->with('error', 'Hanya seller yang dapat mengakses chat.');
        }

        $chats = Chat::where('seller_id', $user->id)
            ->with(['buyer', 'car'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($chat) {
                // Query langsung untuk mendapatkan pesan terbaru berdasarkan id (auto increment)
                $chat->last_message = Message::where('chat_id', $chat->id)
                    ->orderBy('id', 'desc')
                    ->first();
                return $chat;
            });

        return view('chat.seller-index', compact('chats'));
    }

    /**
     * Store a new message
     */
    public function store(Request $request, $chatId)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($chatId);

        // Verify user is part of this chat
        if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Update chat last_message_at
        $chat->update(['last_message_at' => now()]);

        // Mark other user's messages as read if they're viewing
        Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    /**
     * Get messages for a chat (AJAX)
     */
    public function getMessages($chatId)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($chatId);

        // Verify user is part of this chat
        if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $chat->messages()->with('sender')->orderBy('created_at', 'asc')->get();

        // Mark messages as read
        Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
        ]);
    }
}

