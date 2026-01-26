@extends('template.temp')

@section('content')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option set-bg" data-setbg="{{ asset('garasi62/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Chat dengan {{ isset($otherUser) ? $otherUser->name : (isset($seller) ? $seller->name : 'User') }}</h2>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                            <span>Chat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Chat Section Begin -->
    <section class="chat-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="chat-container">
                        <!-- Chat Header -->
                        <div class="chat-header">
                            <div class="chat-header-info">
                                @if(Auth::user()->role === 'seller')
                                    <a href="{{ route('chat.seller.index') }}" class="chat-back-btn">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                @elseif(Auth::user()->role === 'buyer')
                                    <a href="{{ route('chat.index') }}" class="chat-back-btn">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                @endif
                                <div class="chat-avatar">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="chat-header-details">
                                    <h4>{{ isset($otherUser) ? $otherUser->name : (isset($seller) ? $seller->name : 'User') }}</h4>
                                    @if(isset($car) && $car)
                                    <p><i class="fa fa-car"></i> {{ $car->brand }} {{ $car->nama ?? '' }}</p>
                                    @else
                                    <p><i class="fa fa-envelope"></i> {{ isset($otherUser) ? $otherUser->email : (isset($seller) ? $seller->email : '') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div class="chat-messages" id="chatMessages">
                            @if($messages && $messages->count() > 0)
                                @foreach($messages as $message)
                                    @php
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
                                            if (is_string($message->created_at)) {
                                                $createdAt = \Carbon\Carbon::parse($message->created_at);
                                            } elseif (is_object($message->created_at)) {
                                                $createdAt = $message->created_at;
                                            }
                                        }

                                        $isMyMessage = ($message->sender_id ?? 0) === Auth::id();
                                        $isDeleted = (isset($message->deleted_at) && $message->deleted_at) || (isset($message->is_deleted_for_sender) && $message->is_deleted_for_sender && $isMyMessage);
                                        $isEdited = isset($message->edited_at) && $message->edited_at;
                                        $hasReply = isset($message->reply_to) && $message->reply_to;
                                        
                                        // Check if message has been read by recipient
                                        $isReadByRecipient = false;
                                        if ($isMyMessage) {
                                            $recipientId = $chat->buyer_id == Auth::id() ? $chat->seller_id : $chat->buyer_id;
                                            $readBy = is_array($message->read_by ?? null) ? $message->read_by : [];
                                            // Only check if recipient ID is in read_by array
                                            $isReadByRecipient = in_array($recipientId, $readBy);
                                        }
                                        
                                        $repliedToMessage = null;
                                        if ($hasReply && isset($message->replied_to_message)) {
                                            $repliedToMessage = is_object($message->replied_to_message) ? $message->replied_to_message : (object)($message->replied_to_message ?? []);
                                        }
                                    @endphp
                                    <div class="message-item {{ $isMyMessage ? 'message-sent' : 'message-received' }}" data-message-id="{{ $message->id ?? '' }}">
                                        <div class="message-content">
                                            @if($hasReply && $repliedToMessage)
                                            <div class="message-reply-preview">
                                                <div class="reply-line"></div>
                                                <div class="reply-content">
                                                    <span class="reply-sender">{{ $repliedToMessage->sender_name ?? 'User' }}</span>
                                                    <span class="reply-text">{{ Str::limit($repliedToMessage->message ?? '', 50) }}</span>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="message-header">
                                                <span class="message-sender">{{ $senderName }}</span>
                                                <span class="message-time">
                                                    {{ $createdAt->format('H:i') }}
                                                    @if($isEdited)
                                                        <span class="message-edited">(diedit)</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="message-text">
                                                @if($isDeleted)
                                                    <em class="text-muted">Pesan ini telah dihapus</em>
                                                @else
                                                    {{ $message->message ?? '' }}
                                                @endif
                                            </div>
                                            @if(!$isDeleted)
                                            <div class="message-actions">
                                                @if(!$isMyMessage)
                                                    <button class="message-action-btn" onclick="replyToMessage('{{ $message->id ?? '' }}', '{{ $senderName }}', '{{ Str::limit($message->message ?? '', 50) }}')" title="Balas">
                                                        <i class="fa fa-reply"></i> <span style="font-size: 11px;">Balas</span>
                                                    </button>
                                                @else
                                                    @if(!$isReadByRecipient)
                                                    <button class="message-action-btn" onclick="editMessage('{{ $message->id ?? '' }}', '{{ addslashes($message->message ?? '') }}')" title="Edit">
                                                        <i class="fa fa-edit"></i> <span style="font-size: 11px;">Edit</span>
                                                    </button>
                                                    @endif
                                                    <button class="message-action-btn" onclick="deleteMessage('{{ $message->id ?? '' }}')" title="Hapus">
                                                        <i class="fa fa-trash"></i> <span style="font-size: 11px;">Hapus</span>
                                                    </button>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Reply Preview -->
                        <div class="reply-preview-container" id="replyPreview" style="display: none;">
                            <div class="reply-preview-content">
                                <div class="reply-preview-header">
                                    <i class="fa fa-reply"></i>
                                    <span>Membalas:</span>
                                    <button class="reply-preview-close" onclick="cancelReply()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="reply-preview-text" id="replyPreviewText"></div>
                            </div>
                        </div>

                        <!-- Chat Input -->
                        <div class="chat-input-container">
                            <form id="chatForm" class="chat-form" onsubmit="return false;">
                                @csrf
                                <input type="hidden" id="replyTo" name="reply_to" value="">
                                <div class="chat-input-wrapper">
                                    <input type="text" id="messageInput" class="chat-input" placeholder="Ketik pesan..." autocomplete="off">
                                    <button type="button" id="sendMessageBtn" class="chat-send-btn">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Chat Section End -->

    <style>
        .chat-section {
            padding: 40px 0;
            min-height: calc(100vh - 300px);
        }

        .chat-container {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 65vh;
            max-height: 700px;
        }

        .chat-header {
            background: linear-gradient(135deg, #df2d24, #b91c1c);
            color: #fff;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-back-btn {
            color: #fff;
            font-size: 18px;
            text-decoration: none;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }

        .chat-back-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .chat-header-details h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .chat-header-details p {
            margin: 2px 0 0 0;
            font-size: 12px;
            opacity: 0.9;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 5px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #df2d24;
            border-radius: 5px;
        }

        .message-item {
            display: flex;
            margin-bottom: 12px;
            animation: fadeIn 0.3s ease-out;
        }

        .message-sent {
            justify-content: flex-end;
        }

        .message-received {
            justify-content: flex-start;
        }

        .message-content {
            max-width: 70%;
            padding: 10px 14px;
            border-radius: 5px;
            position: relative;
        }

        .message-sent .message-content {
            background: linear-gradient(135deg, #df2d24, #b91c1c);
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .message-received .message-content {
            background: #fff;
            color: #1a1a1a;
            border: 1px solid #e0e0e0;
            border-bottom-left-radius: 4px;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
            font-size: 11px;
            opacity: 0.8;
        }

        .message-sender {
            font-weight: 700;
        }

        .message-time {
            margin-left: 8px;
        }

        .message-text {
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .chat-input-container {
            padding: 14px 20px;
            background: #fff;
            border-top: 1px solid #e0e0e0;
        }

        .chat-input-wrapper {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .chat-input {
            flex: 1;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
        }

        .chat-input:focus {
            border-color: #df2d24;
            box-shadow: 0 0 0 3px rgba(223, 45, 36, 0.1);
        }

        .chat-send-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #df2d24, #b91c1c);
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(223, 45, 36, 0.3);
        }

        .chat-send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(223, 45, 36, 0.4);
        }

        .chat-send-btn:active {
            transform: translateY(0);
        }

        .message-reply-preview {
            margin-bottom: 8px;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.1);
            border-left: 3px solid rgba(255, 255, 255, 0.5);
            border-radius: 4px;
            font-size: 12px;
        }

        .message-sent .message-reply-preview {
            background: rgba(255, 255, 255, 0.2);
            border-left-color: rgba(255, 255, 255, 0.5);
        }

        .message-received .message-reply-preview {
            background: rgba(0, 0, 0, 0.05);
            border-left-color: #df2d24;
        }

        .reply-line {
            height: 2px;
            width: 20px;
            background: currentColor;
            margin-bottom: 4px;
            opacity: 0.5;
        }

        .reply-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .reply-sender {
            font-weight: 700;
            opacity: 0.9;
        }

        .reply-text {
            opacity: 0.8;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .message-edited {
            font-size: 10px;
            opacity: 0.7;
            font-style: italic;
        }

        .message-actions {
            display: none;
            gap: 8px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .message-received .message-actions {
            border-top-color: rgba(0, 0, 0, 0.1);
        }

        .message-item:hover .message-actions {
            display: flex;
        }

        .message-action-btn {
            background: transparent;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 14px;
            opacity: 0.8;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .message-action-btn i {
            font-size: 14px;
        }

        .message-action-btn:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .message-received .message-action-btn:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        .reply-preview-container {
            padding: 12px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
        }

        .reply-preview-content {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .reply-preview-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #6c757d;
            font-weight: 600;
        }

        .reply-preview-close {
            margin-left: auto;
            background: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .reply-preview-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #df2d24;
        }

        .reply-preview-text {
            padding: 8px 12px;
            background: #fff;
            border-left: 3px solid #df2d24;
            border-radius: 4px;
            font-size: 13px;
            color: #6c757d;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 767px) {
            .chat-container {
                height: calc(100vh - 200px);
            }

            .message-content {
                max-width: 85%;
            }
        }

        /* SweetAlert2 Custom Styles */
        .swal2-popup.swal-delete-popup {
            border-radius: 12px;
            padding: 30px;
        }

        .swal2-popup.swal-edit-popup {
            border-radius: 12px;
            padding: 30px;
            max-width: 600px;
        }

        .swal-edit-confirm {
            background: linear-gradient(135deg, #df2d24, #b91c1c) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px 28px !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            transition: all 0.3s !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            min-width: 160px !important;
        }

        .swal-edit-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(223, 45, 36, 0.4) !important;
        }

        .swal-edit-confirm i {
            font-size: 15px;
            display: inline-block;
        }

        .swal-edit-cancel {
            border-radius: 8px !important;
            padding: 12px 28px !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            transition: all 0.3s !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            min-width: 120px !important;
        }

        .swal-edit-cancel:hover {
            background: #f3f4f6 !important;
            transform: translateY(-1px);
        }

        .swal-edit-cancel i {
            font-size: 15px;
            display: inline-block;
        }

        .swal-delete-confirm {
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
            transition: all 0.3s !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        .swal-delete-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4) !important;
        }

        .swal-delete-confirm i {
            font-size: 14px;
        }

        .swal-delete-cancel {
            border-radius: 8px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
            transition: all 0.3s !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        .swal-delete-cancel:hover {
            background: #f3f4f6 !important;
        }

        .swal-delete-cancel i {
            font-size: 14px;
        }

        .swal2-textarea {
            transition: all 0.3s;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .swal2-textarea:focus {
            border-color: #df2d24 !important;
            box-shadow: 0 0 0 3px rgba(223, 45, 36, 0.1) !important;
        }

        .swal2-textarea:invalid {
            border-color: #dc2626 !important;
        }

        .swal-edit-html {
            text-align: left !important;
        }

        .swal2-html-container .swal2-textarea {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
    </style>

    <!-- Font Awesome (if not already loaded) -->
    @if(!isset($fontAwesomeLoaded))
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endif
    
    <!-- Load Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <!-- Load Laravel Echo -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.min.js"></script>
    <script>
        // Initialize Laravel Echo
        window.Pusher = Pusher;
        
        const pusherKey = '{{ $pusherConfig['key'] ?? config('broadcasting.connections.pusher.key') }}';
        const pusherCluster = '{{ $pusherConfig['cluster'] ?? config('broadcasting.connections.pusher.options.cluster', 'ap1') }}';
        
        console.log('Initializing Echo with key:', pusherKey, 'cluster:', pusherCluster);
        
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: pusherCluster,
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            },
            enabledTransports: ['ws', 'wss'],
            disableStats: true,
        });
        
        console.log('✅ Echo initialized:', window.Echo);
        console.log('🔑 Pusher Key:', pusherKey ? 'Set' : 'Missing');
        console.log('🌍 Cluster:', pusherCluster);
    </script>
    
    <script>
        const chatId = '{{ $chat->id }}';
        const currentUserId = {{ Auth::id() }};
        const recipientId = {{ $chat->buyer_id == Auth::id() ? $chat->seller_id : $chat->buyer_id }};
        let lastMessageId = 0;
        const initialMessageIds = @json($messages->map(function($msg) { 
            return is_object($msg) ? ($msg->id ?? null) : ($msg['id'] ?? null); 
        })->filter()->values()->toArray());
        const renderedMessageIds = new Set(initialMessageIds);
        
        console.log('Chat ID:', chatId);
        console.log('Current User ID:', currentUserId);
        console.log('Recipient ID:', recipientId);
        console.log('Initial messages:', @json($messages));

        // Auto scroll to bottom
        function scrollToBottom() {
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        let currentReplyTo = null;

        function appendMessage({ id, sender_id, message, created_at, sender_name, reply_to, replied_to_message, edited_at, deleted_at, is_deleted_for_sender, read_by, is_read }) {
            const messagesContainer = document.getElementById('chatMessages');
            if (!messagesContainer) {
                console.error('Messages container not found!');
                return;
            }
            
            const messageDiv = document.createElement('div');
            const timeText = new Date(created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const isMyMessage = sender_id === currentUserId;
            const isDeleted = deleted_at || (is_deleted_for_sender && isMyMessage);
            const isEdited = edited_at;
            const hasReply = reply_to && replied_to_message;

            messageDiv.className = `message-item ${isMyMessage ? 'message-sent' : 'message-received'}`;
            messageDiv.dataset.id = id;
            
            let replyPreview = '';
            if (hasReply) {
                replyPreview = `
                    <div class="message-reply-preview">
                        <div class="reply-line"></div>
                        <div class="reply-content">
                            <span class="reply-sender">${replied_to_message.sender_name || 'User'}</span>
                            <span class="reply-text">${replied_to_message.message ? replied_to_message.message.substring(0, 50) : ''}</span>
                        </div>
                    </div>
                `;
            }

            // Check if message has been read by recipient (for edit button visibility)
            // Only check if this is my message
            let isReadByRecipient = false;
            if (isMyMessage) {
                if (read_by && Array.isArray(read_by)) {
                    isReadByRecipient = read_by.includes(recipientId);
                } else if (is_read) {
                    // Fallback: if is_read is true, assume recipient has read it
                    isReadByRecipient = true;
                }
            }
            
            let messageActions = '';
            if (!isDeleted) {
                if (!isMyMessage) {
                    messageActions = `
                        <div class="message-actions">
                            <button class="message-action-btn" onclick="replyToMessage('${id}', '${sender_name || 'User'}', '${(message || '').replace(/'/g, "\\'").substring(0, 50)}')" title="Balas">
                                <i class="fa fa-reply"></i> <span style="font-size: 11px;">Balas</span>
                            </button>
                        </div>
                    `;
                } else {
                    // Only show edit button if message hasn't been read by recipient
                    const editButton = !isReadByRecipient ? `
                        <button class="message-action-btn" onclick="editMessage('${id}', '${(message || '').replace(/'/g, "\\'")}')" title="Edit">
                            <i class="fa fa-edit"></i> <span style="font-size: 11px;">Edit</span>
                        </button>
                    ` : '';
                    messageActions = `
                        <div class="message-actions">
                            ${editButton}
                            <button class="message-action-btn" onclick="deleteMessage('${id}')" title="Hapus">
                                <i class="fa fa-trash"></i> <span style="font-size: 11px;">Hapus</span>
                            </button>
                        </div>
                    `;
                }
            }

            messageDiv.innerHTML = `
                <div class="message-content">
                    ${replyPreview}
                    <div class="message-header">
                        <span class="message-sender">${sender_name ?? 'User'}</span>
                        <span class="message-time">
                            ${timeText}
                            ${isEdited ? '<span class="message-edited">(diedit)</span>' : ''}
                        </span>
                    </div>
                    <div class="message-text">
                        ${isDeleted ? '<em class="text-muted">Pesan ini telah dihapus</em>' : (message || '')}
                    </div>
                    ${messageActions}
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
            lastMessageId = Math.max(lastMessageId, parseInt(id) || 0);
            
            // Always scroll to bottom after adding message
            setTimeout(() => {
                scrollToBottom();
            }, 100);
        }

        // Function to send message
        function sendChatMessage() {
        function replyToMessage(messageId, senderName, messageText) {
            currentReplyTo = messageId;
            document.getElementById('replyTo').value = messageId;
            document.getElementById('replyPreviewText').textContent = `${senderName}: ${messageText}`;
            document.getElementById('replyPreview').style.display = 'block';
            document.getElementById('messageInput').focus();
        }

        function cancelReply() {
            currentReplyTo = null;
            document.getElementById('replyTo').value = '';
            document.getElementById('replyPreview').style.display = 'none';
        }

        function editMessage(messageId, currentMessage) {
            // Escape HTML untuk keamanan
            const escapedMessage = currentMessage
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
            
            Swal.fire({
                title: '<div style="text-align: center; padding: 10px 0;"><i class="fa fa-edit" style="font-size: 36px; color: #df2d24; margin-bottom: 12px; display: inline-block;"></i><div style="font-size: 22px; font-weight: 700; color: #1a1a1a; margin-top: 8px;">Edit Pesan</div></div>',
                html: `
                    <div style="text-align: left; margin-top: 25px; padding: 0 5px;">
                        <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #1a1a1a; font-size: 15px;">
                            <i class="fa fa-comment" style="margin-right: 6px; color: #df2d24;"></i>Pesan:
                        </label>
                        <textarea id="swal-edit-message" class="swal2-textarea" style="width: 100%; min-height: 150px; padding: 16px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; resize: vertical; outline: none; transition: all 0.3s; line-height: 1.6; background: #fafafa;" maxlength="1000" placeholder="Ketik pesan Anda di sini...">${escapedMessage}</textarea>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding: 0 2px;">
                            <div style="font-size: 12px; color: #9ca3af; display: flex; align-items: center; gap: 4px;">
                                <i class="fa fa-info-circle"></i>
                                <span>Pesan yang sudah dibaca tidak bisa di-edit</span>
                            </div>
                            <div style="text-align: right; font-size: 13px; color: #6c757d; font-weight: 500; display: flex; align-items: center; gap: 4px;">
                                <span id="swal-edit-count" style="color: #1a1a1a; font-weight: 700; font-size: 14px;">0</span>
                                <span>/</span>
                                <span>1000 karakter</span>
                            </div>
                        </div>
                    </div>
                `,
                icon: null,
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-save" style="margin-right: 6px;"></i>Simpan Perubahan',
                cancelButtonText: '<i class="fa fa-times" style="margin-right: 6px;"></i>Batal',
                confirmButtonColor: '#df2d24',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusConfirm: false,
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal-edit-popup',
                    confirmButton: 'swal-edit-confirm',
                    cancelButton: 'swal-edit-cancel',
                    htmlContainer: 'swal-edit-html'
                },
                didOpen: () => {
                    const textarea = document.getElementById('swal-edit-message');
                    const countSpan = document.getElementById('swal-edit-count');
                    
                    if (textarea && countSpan) {
                        // Set initial count
                        countSpan.textContent = textarea.value.length;
                        
                        // Focus and select all
                        textarea.focus();
                        textarea.select();
                        
                        // Add event listener for character count
                        textarea.addEventListener('input', function() {
                            const length = this.value.length;
                            countSpan.textContent = length;
                            
                            if (length > 1000) {
                                countSpan.style.color = '#dc2626';
                            } else if (length > 900) {
                                countSpan.style.color = '#f59e0b';
                            } else {
                                countSpan.style.color = '#1a1a1a';
                            }
                        });
                        
                        // Add focus style
                        textarea.addEventListener('focus', function() {
                            this.style.borderColor = '#df2d24';
                            this.style.boxShadow = '0 0 0 3px rgba(223, 45, 36, 0.1)';
                        });
                        
                        textarea.addEventListener('blur', function() {
                            if (this.value.length <= 1000) {
                                this.style.borderColor = '#e0e0e0';
                                this.style.boxShadow = 'none';
                            }
                        });
                    }
                },
                preConfirm: () => {
                    const textarea = document.getElementById('swal-edit-message');
                    const newMessage = textarea ? textarea.value.trim() : '';
                    
                    if (!newMessage) {
                        Swal.showValidationMessage('Pesan tidak boleh kosong');
                        return false;
                    }
                    
                    if (newMessage.length > 1000) {
                        Swal.showValidationMessage('Pesan tidak boleh lebih dari 1000 karakter');
                        return false;
                    }
                    
                    if (newMessage === currentMessage) {
                        Swal.showValidationMessage('Pesan tidak berubah');
                        return false;
                    }
                    
                    return newMessage;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const newMessage = result.value;
                    
                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    fetch(`{{ route('chat.edit', [$chat->id, 'MESSAGE_ID']) }}`.replace('MESSAGE_ID', messageId), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: newMessage })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                            if (messageElement) {
                                const messageText = messageElement.querySelector('.message-text');
                                if (messageText) {
                                    messageText.textContent = newMessage;
                                }
                                const messageTime = messageElement.querySelector('.message-time');
                                if (messageTime && !messageTime.querySelector('.message-edited')) {
                                    messageTime.innerHTML += '<span class="message-edited">(diedit)</span>';
                                }
                            }
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pesan berhasil diubah',
                                confirmButtonColor: '#df2d24',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.error || 'Gagal mengedit pesan',
                                confirmButtonColor: '#df2d24'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error editing message:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengedit pesan',
                            confirmButtonColor: '#df2d24'
                        });
                    });
                }
            });
        }

        function deleteMessage(messageId) {
            Swal.fire({
                title: 'Hapus Pesan?',
                html: `
                    <div style="text-align: center; padding: 20px 0;">
                        <div style="font-size: 48px; color: #dc2626; margin-bottom: 15px;">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <p style="font-size: 16px; color: #1a1a1a; margin-bottom: 10px; font-weight: 600;">
                            Apakah Anda yakin ingin menghapus pesan ini?
                        </p>
                        <p style="font-size: 14px; color: #6c757d; margin: 0;">
                            Tindakan ini tidak dapat dibatalkan
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'swal-delete-popup',
                    confirmButton: 'swal-delete-confirm',
                    cancelButton: 'swal-delete-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    fetch(`{{ route('chat.delete', [$chat->id, 'MESSAGE_ID']) }}`.replace('MESSAGE_ID', messageId), {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                            if (messageElement) {
                                const messageText = messageElement.querySelector('.message-text');
                                if (messageText) {
                                    messageText.innerHTML = '<em class="text-muted">Pesan ini telah dihapus</em>';
                                }
                                const messageActions = messageElement.querySelector('.message-actions');
                                if (messageActions) {
                                    messageActions.remove();
                                }
                            }
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pesan berhasil dihapus',
                                confirmButtonColor: '#df2d24',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.error || 'Gagal menghapus pesan',
                                confirmButtonColor: '#df2d24'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting message:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus pesan',
                            confirmButtonColor: '#df2d24'
                        });
                    });
                }
            });
        }

        // Send message
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('messageInput');
            if (!messageInput) {
                console.error('Message input not found');
                return false;
            }
            
            const message = messageInput.value.trim();
            
            if (!message) {
                return false;
            }

            const sendBtn = document.getElementById('sendMessageBtn') || document.querySelector('.chat-send-btn');
            if (!sendBtn) {
                console.error('Send button not found');
                return false;
            }

            const originalHTML = sendBtn.innerHTML;
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            const chatId = '{{ $chat->id }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value || '';
            
            if (!csrfToken) {
                console.error('CSRF token not found');
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalHTML;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'CSRF token tidak ditemukan. Silakan refresh halaman.',
                        confirmButtonColor: '#df2d24'
                    });
                } else {
                    alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
                }
                return false;
            }

            // Ensure chat ID is properly encoded
            const encodedChatId = encodeURIComponent(chatId);
            const url = `/chat/${encodedChatId}/message`;
            
            console.log('Sending message to:', url);
            console.log('Message:', message);
            console.log('Chat ID:', chatId);
            console.log('Encoded Chat ID:', encodedChatId);
            console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');
            const replyTo = document.getElementById('replyTo').value;
            const url = replyTo ? `{{ route('chat.reply', $chat->id) }}` : `{{ route('chat.store', $chat->id) }}`;
            const body = replyTo ? { message: message, reply_to: replyTo } : { message: message };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(body)
            })
            .then(response => {
                console.log('Response status:', response.status);
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
                console.log('Response data:', data);
                if (data.success) {
                    messageInput.value = '';
                    cancelReply(); // Clear reply preview
                    
                    // Get sender name safely
                    let senderName = 'User';
                    if (data.message && data.message.sender && data.message.sender.name) {
                        senderName = data.message.sender.name;
                    } else if (data.message && data.message.sender_name) {
                        senderName = data.message.sender_name;
                    }
                    
                    const msg = {
                        id: data.message.id,
                        sender_id: data.message.sender_id,
                        sender_name: senderName,
                        message: data.message.message,
                        reply_to: data.message.reply_to || null,
                        replied_to_message: data.message.replied_to_message || null,
                        read_by: data.message.read_by || [],
                        is_read: data.message.is_read || false,
                        created_at: data.message.created_at
                    };

                    // Always add message ID to rendered set first to prevent duplicate
                    if (!renderedMessageIds.has(msg.id)) {
                        renderedMessageIds.add(msg.id);
                        appendMessage(msg);
                        console.log('✅ Message sent and displayed immediately:', msg.id);
                    } else {
                        console.log('⚠️ Message already displayed, skipping:', msg.id);
                    }
                } else {
                    console.error('Failed to send message:', data);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.error || 'Gagal mengirim pesan. Silakan coba lagi.',
                            confirmButtonColor: '#df2d24'
                        });
                    } else {
                        alert('Gagal mengirim pesan: ' + (data.error || 'Silakan coba lagi.'));
                    }
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mengirim pesan: ' + error.message,
                        confirmButtonColor: '#df2d24'
                    });
                } else {
                    alert('Gagal mengirim pesan: ' + error.message);
                }
            })
            .finally(() => {
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalHTML;
                messageInput.focus();
            });
            
            return false;
        }

        // Attach event listeners when DOM is ready
        function attachChatEventListeners() {
            const chatForm = document.getElementById('chatForm');
            const messageInput = document.getElementById('messageInput');
            const sendBtn = document.getElementById('sendMessageBtn') || document.querySelector('.chat-send-btn');
            
            if (!chatForm || !messageInput || !sendBtn) {
                console.warn('Chat form elements not found, retrying...');
                setTimeout(attachChatEventListeners, 100);
                return;
            }
            
            // Remove existing listeners by cloning
            const newForm = chatForm.cloneNode(true);
            chatForm.parentNode.replaceChild(newForm, chatForm);
            
            // Get new references
            const newChatForm = document.getElementById('chatForm');
            const newMessageInput = document.getElementById('messageInput');
            const newSendBtn = document.getElementById('sendMessageBtn') || document.querySelector('.chat-send-btn');
            
            // Prevent form submission
            if (newChatForm) {
                newChatForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    sendChatMessage();
                    return false;
                }, true);
                
                // Also set onsubmit to prevent default
                newChatForm.onsubmit = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sendChatMessage();
                    return false;
                };
            }
            
            // Handle button click
            if (newSendBtn) {
                newSendBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sendChatMessage();
                });
            }
            
            // Handle Enter key
            if (newMessageInput) {
                newMessageInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        e.stopPropagation();
                        sendChatMessage();
                    }
                });
            }
            
            console.log('✅ Chat event listeners attached successfully');
        }
        
        // Try to attach immediately if DOM is already loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', attachChatEventListeners);
        } else {
            // DOM already loaded
            attachChatEventListeners();
        }
        
        // Also try after a short delay as fallback
        setTimeout(attachChatEventListeners, 500);

        // Listen real-time via Echo
        if (typeof Echo !== 'undefined' && window.Echo) {
            console.log('🔌 Connecting to Pusher channel: chat.' + chatId);
            
            const channel = window.Echo.private(`chat.${chatId}`);
            
            channel.subscribed(() => {
                console.log('✅ Successfully subscribed to channel: chat.' + chatId);
                console.log('👂 Listening for MessageSent events...');
            });
            
            channel.error((error) => {
                console.error('❌ Echo subscription error:', error);
                console.error('Error details:', JSON.stringify(error, null, 2));
                
                if (error.status === 403) {
                    console.error('⚠️ Authorization failed! Check if user has access to this chat.');
                } else if (error.status === 401) {
                    console.error('⚠️ Authentication failed! User may not be logged in.');
                }
            });
            
            // Listen for connection state changes
            window.Echo.connector.pusher.connection.bind('connected', () => {
                console.log('✅ Pusher connected');
            });
            
            window.Echo.connector.pusher.connection.bind('disconnected', () => {
                console.warn('⚠️ Pusher disconnected');
            });
            
            window.Echo.connector.pusher.connection.bind('error', (err) => {
                console.error('❌ Pusher connection error:', err);
            });
            
            // Listen for MessageSent event
            channel.listen('.MessageSent', (e) => {
                console.log('📨 Message received via Pusher:', e);
                console.log('📨 Event data:', JSON.stringify(e, null, 2));
                
                if (!e.message) {
                    console.error('❌ Invalid event data: message is missing');
                    return;
                }
                
                const msg = {
                    id: e.message.id,
                    sender_id: e.message.sender_id,
                    sender_name: e.message.sender_name || 'User',
                    message: e.message.message,
                    reply_to: e.message.reply_to || null,
                    replied_to_message: e.message.replied_to_message || null,
                    edited_at: e.message.edited_at || null,
                    deleted_at: e.message.deleted_at || null,
                    is_deleted_for_sender: e.message.is_deleted_for_sender || false,
                    read_by: e.message.read_by || [],
                    is_read: e.message.is_read || false,
                    created_at: e.message.created_at
                };

                // Check if message already rendered to prevent duplicates
                if (renderedMessageIds.has(msg.id)) {
                    console.log('⚠️ Message already rendered, skipping:', msg.id);
                    return;
                }

                // Add to rendered set and display
                renderedMessageIds.add(msg.id);
                appendMessage(msg);
                console.log('✅ New message displayed via Pusher:', msg.id);
            });

            // Listen for MessageReplied event (for notifications)
            channel.listen('.MessageReplied', (e) => {
                console.log('🔔 Reply notification received via Pusher:', e);
                
                if (!e.message) {
                    console.error('❌ Invalid reply event data: message is missing');
                    return;
                }

                // Show browser notification if available
                if ('Notification' in window && Notification.permission === 'granted') {
                    const notification = new Notification(e.notification.title || 'Pesan Dibalas', {
                        body: e.notification.body || `${e.message.sender_name} membalas pesan Anda`,
                        icon: '/img/logo.png',
                        tag: `chat-${e.chat_id}`,
                    });

                    notification.onclick = function() {
                        window.focus();
                        notification.close();
                    };
                }

                // If notification is for other user (not current user), show alert
                if (e.message.sender_id !== currentUserId && e.notification) {
                    // You can customize this notification display
                    console.log('📢 Notification:', e.notification.body);
                }

                // Display the replied message
                const msg = {
                    id: e.message.id,
                    sender_id: e.message.sender_id,
                    sender_name: e.message.sender_name || 'User',
                    message: e.message.message,
                    reply_to: e.message.reply_to || null,
                    replied_to_message: e.message.replied_to_message || null,
                    read_by: e.message.read_by || [],
                    is_read: e.message.is_read || false,
                    created_at: e.message.created_at
                };

                if (!renderedMessageIds.has(msg.id)) {
                    renderedMessageIds.add(msg.id);
                    appendMessage(msg);
                    console.log('✅ Reply message displayed via Pusher:', msg.id);
                }
            });
            
            // Also listen without dot prefix (fallback)
            channel.listen('MessageSent', (e) => {
                console.log('📨 Message received via Pusher (fallback):', e);
                console.log('📨 Event data:', JSON.stringify(e, null, 2));
                
                if (!e.message) {
                    console.error('❌ Invalid event data: message is missing');
                    return;
                }
                
                const msg = {
                    id: e.message.id,
                    sender_id: e.message.sender_id,
                    sender_name: e.message.sender_name || 'User',
                    message: e.message.message,
                    reply_to: e.message.reply_to || null,
                    replied_to_message: e.message.replied_to_message || null,
                    edited_at: e.message.edited_at || null,
                    deleted_at: e.message.deleted_at || null,
                    is_deleted_for_sender: e.message.is_deleted_for_sender || false,
                    read_by: e.message.read_by || [],
                    is_read: e.message.is_read || false,
                    created_at: e.message.created_at
                };

                // Check if message already rendered to prevent duplicates
                if (renderedMessageIds.has(msg.id)) {
                    console.log('⚠️ Message already rendered (fallback), skipping:', msg.id);
                    return;
                }

                // Add to rendered set and display
                renderedMessageIds.add(msg.id);
                appendMessage(msg);
                console.log('✅ New message displayed via Pusher (fallback):', msg.id);
            });

            // Request notification permission on page load
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        } else {
            console.error('Laravel Echo is not loaded! Make sure Pusher credentials are set in .env');
        }

        // Mark messages as read when chat is opened
        function markMessagesAsRead() {
            fetch(`{{ route('chat.mark-read', $chat->id) }}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('✅ Messages marked as read');
                    // Close popup notification if exists
                    if (typeof closeChatNotification === 'function') {
                        closeChatNotification();
                    }
                    // Update badge count
                    if (typeof updateChatBadge === 'function') {
                        updateChatBadge();
                    }
                    // Update chat list if exists
                    if (typeof refreshChatList === 'function') {
                        refreshChatList();
                    }
                }
            })
            .catch(error => {
                console.error('Error marking messages as read:', error);
            });
        }

        // Initialize chat scripts (for AJAX loaded content)
        function initChatScripts() {
            // Re-initialize all chat functionality
            if (typeof scrollToBottom === 'function') {
                scrollToBottom();
            }
            if (typeof markMessagesAsRead === 'function') {
                markMessagesAsRead();
            }
        }

        // Mark as read when page loads
        document.addEventListener('DOMContentLoaded', function() {
            markMessagesAsRead();
        });

        // Also mark as read when user scrolls to bottom (indicating they're reading)
        let lastScrollTop = 0;
        const messagesContainer = document.getElementById('chatMessages');
        if (messagesContainer) {
            messagesContainer.addEventListener('scroll', function() {
                const scrollTop = messagesContainer.scrollTop;
                const scrollHeight = messagesContainer.scrollHeight;
                const clientHeight = messagesContainer.clientHeight;
                
                // If scrolled to bottom, mark as read
                if (scrollTop + clientHeight >= scrollHeight - 50) {
                    markMessagesAsRead();
                }
            });
        }

        // Initial scroll to bottom
        scrollToBottom();
    </script>
@endsection

