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
                                    <div class="chat-status">
                                        <span class="status-dot" id="onlineDot"></span>
                                        <span class="status-text" id="onlineText">Offline</span>
                                    </div>
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
                                    @endphp
                                    <div class="message-item {{ ($message->sender_id ?? 0) === Auth::id() ? 'message-sent' : 'message-received' }}">
                                    <div class="message-content">
                                        <div class="message-actions">
                                            <button class="btn-icon reply-btn" onclick="setReply('{{ $message->id }}', '{{ addslashes($senderName) }}', '{{ str_replace(array("\r", "\n"), array('\r', '\n'), addslashes($message->message)) }}')"><i class="fa fa-reply"></i></button>
                                            @if(($message->sender_id ?? 0) === Auth::id() && !($message->is_read ?? false))
                                                <button class="btn-icon edit-btn" onclick="openEditModal('{{ $message->id }}', '{{ str_replace(array("\r", "\n"), array('\r', '\n'), addslashes($message->message)) }}')"><i class="fa fa-pencil"></i></button>
                                            @endif
                                            @if(($message->sender_id ?? 0) === Auth::id())
                                                <button class="btn-icon delete-btn" onclick="deleteMessage('{{ $message->id }}')"><i class="fa fa-trash"></i></button>
                                            @endif
                                        </div>
                                        <div class="message-header">
                                            <span class="message-sender">{{ $senderName }}</span>
                                            <span class="message-time">{{ $createdAt->format('H:i') }}</span>
                                        </div>
                                        @if(isset($message->reply_to_message) && $message->reply_to_message)
                                            <div class="reply-preview">
                                                <div class="reply-preview-sender">{{ $message->reply_to_message->sender_name ?? 'User' }}</div>
                                                <div class="reply-preview-text">{{ $message->reply_to_message->message ?? '' }}</div>
                                            </div>
                                        @endif
                                        <div class="message-text" id="msg-text-{{ $message->id }}">{{ $message->message ?? '' }}</div>
                                    </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div id="replyBar" class="reply-bar" style="display: none;">
                            <div class="reply-bar-content">
                                <div class="reply-bar-label">Membalas</div>
                                <div class="reply-bar-text" id="replyBarText"></div>
                            </div>
                            <button type="button" class="reply-bar-close" onclick="clearReply()">&times;</button>
                        </div>

                        <div id="typingBar" class="typing-bar" style="display: none;">
                            <span class="typing-text" id="typingIndicator">sedang mengetik...</span>
                        </div>

                        <div class="chat-input-container">
                            <form id="chatForm" class="chat-form" onsubmit="return false;">
                                @csrf
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

    <div id="editModal" class="custom-modal">
        <div class="custom-modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h4>Edit Pesan</h4>
            <div class="form-group mt-3">
                <textarea id="editMessageContent" class="form-control" rows="3"></textarea>
                <input type="hidden" id="editMessageId">
            </div>
            <div class="text-right mt-3">
                <button class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                <button class="btn btn-primary" onclick="submitEditMessage()" style="background-color: #df2d24; border-color: #df2d24;">Simpan</button>
            </div>
        </div>
    </div>

    <style>
        .chat-section {
            padding: 40px 0;
            min-height: calc(100vh - 300px);
        }

        .message-actions {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            gap: 6px;
            padding: 6px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            opacity: 0;
            transform: translateY(-4px);
            transition: all 0.2s;
            pointer-events: none;
            z-index: 10;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
        
        .message-content:hover .message-actions {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        
        .btn-icon {
            background: transparent;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #444;
            transition: all 0.2s;
        }
        
        .btn-icon:hover {
            background: rgba(223, 45, 36, 0.12);
            color: #df2d24;
        }

        .message-sent .message-actions {
            background: rgba(255, 255, 255, 0.15);
        }

        .message-sent .btn-icon {
            color: #fff;
        }
        
        .message-sent .btn-icon:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .custom-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }
        
        .custom-modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 25px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            position: relative;
            animation: slideIn 0.3s;
        }
        
        .close-modal {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close-modal:hover,
        .close-modal:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        @keyframes slideIn {
            from {transform: translateY(-50px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
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
            background: linear-gradient(135deg, #e53935, #b91c1c);
            color: #fff;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 20px rgba(185, 28, 28, 0.25);
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 14px;
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
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .chat-back-btn:hover {
            background: rgba(255, 255, 255, 0.28);
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
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        .chat-header-details i{
            color: #fff;
        }

        .chat-header-details h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .chat-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            margin-top: 2px;
            opacity: 0.9;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #9ca3af;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        }

        .status-dot.online {
            background: #10b981;
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.15);
        }

        .status-text {
            color: rgba(255, 255, 255, 0.85);
        }

        .typing-indicator {
            color: rgba(255, 255, 255, 0.85);
            font-style: italic;
        }

        .typing-bar {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            background: #fff7f7;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .typing-text {
            font-size: 12px;
            color: #b91c1c;
            font-style: italic;
        }

        .chat-header-details p {
            margin: 2px 0 0 0;
            font-size: 12px;
            color: #fff;
            opacity: 0.85;
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
            max-width: 72%;
            padding: 12px 14px;
            border-radius: 14px;
            position: relative;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .message-sent .message-content {
            background: linear-gradient(135deg, #e53935, #c62828);
            color: #fff;
            border-bottom-right-radius: 6px;
        }

        .message-received .message-content {
            background: #ffffff;
            color: #1a1a1a;
            border: 1px solid #e0e0e0;
            border-bottom-left-radius: 6px;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 11px;
            opacity: 0.75;
        }

        .message-sender {
            font-weight: 700;
        }

        .message-time {
            margin-left: 8px;
        }

        .message-text {
            font-size: 14px;
            line-height: 1.6;
            word-wrap: break-word;
        }

        .reply-preview {
            border-left: 3px solid rgba(255, 255, 255, 0.6);
            padding: 8px 10px;
            margin-bottom: 8px;
            font-size: 12px;
            opacity: 0.9;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 10px;
        }

        .message-received .reply-preview {
            border-left-color: #df2d24;
            color: #1a1a1a;
            background: #f4f6f8;
        }

        .reply-preview-sender {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .reply-preview-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 240px;
        }

        .reply-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background: #fff7f7;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
        }

        .reply-bar-content {
            flex: 1;
            min-width: 0;
        }

        .reply-bar-label {
            font-size: 12px;
            color: #df2d24;
            font-weight: 700;
        }

        .reply-bar-text {
            font-size: 13px;
            color: #1a1a1a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .reply-bar-close {
            background: transparent;
            border: none;
            font-size: 20px;
            color: #b91c1c;
            cursor: pointer;
            padding: 0 8px;
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

            .message-actions {
                position: static;
                padding: 0;
                background: transparent;
                opacity: 1;
                transform: none;
                pointer-events: auto;
                justify-content: flex-end;
                margin-bottom: 6px;
            }
        }
    </style>

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
        const otherUserId = {{ isset($otherUser) ? (int)$otherUser->id : (isset($seller) ? (int)$seller->id : 0) }};
        let lastMessageId = 0;
        const initialMessageIds = @json($messages->map(function($msg) { 
            return is_object($msg) ? ($msg->id ?? null) : ($msg['id'] ?? null); 
        })->filter()->values()->toArray());
        const renderedMessageIds = new Set(initialMessageIds);
        let currentReplyId = null;
        let currentReplyText = '';
        let currentReplySender = '';
        let oldestMessageId = initialMessageIds.length ? Math.min(...initialMessageIds.map(id => parseInt(id))) : Number.MAX_SAFE_INTEGER;
        let historyLoading = false;
        let historyDone = false;
        let historyPerPage = 30;
        let typingTimeout = null;
        let lastTypingSentAt = 0;
        
        console.log('Chat ID:', chatId);
        console.log('Current User ID:', currentUserId);
        console.log('Initial messages:', @json($messages));

        // Auto scroll to bottom
        function scrollToBottom() {
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function buildMessageElement({ id, sender_id, message, created_at, sender_name, is_read, reply_to_message }) {
            const messageDiv = document.createElement('div');
            const timeText = new Date(created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            messageDiv.className = `message-item ${sender_id === currentUserId ? 'message-sent' : 'message-received'}`;
            messageDiv.dataset.id = id;
            
            const isOwnMessage = sender_id === currentUserId;
            const showEdit = isOwnMessage && !is_read;
            const showDelete = isOwnMessage;
            
            const safeMessage = message.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const safeSender = (sender_name ?? 'User').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            
            const actionsHtml = `
                <div class="message-actions">
                    <button class="btn-icon reply-btn" onclick="setReply('${id}', '${safeSender}', '${safeMessage}')"><i class="fa fa-reply"></i></button>
                    ${showEdit ? `<button class="btn-icon edit-btn" onclick="openEditModal('${id}', '${safeMessage}')"><i class="fa fa-pencil"></i></button>` : ''}
                    ${showDelete ? `<button class="btn-icon delete-btn" onclick="deleteMessage('${id}')"><i class="fa fa-trash"></i></button>` : ''}
                </div>
            `;

            const replyPreviewHtml = reply_to_message ? `
                <div class="reply-preview">
                    <div class="reply-preview-sender">${reply_to_message.sender_name ?? 'User'}</div>
                    <div class="reply-preview-text">${reply_to_message.message ?? ''}</div>
                </div>
            ` : '';

            messageDiv.innerHTML = `
                <div class="message-content">
                    ${actionsHtml}
                    <div class="message-header">
                        <span class="message-sender">${sender_name ?? 'User'}</span>
                        <span class="message-time">${timeText}</span>
                    </div>
                    ${replyPreviewHtml}
                    <div class="message-text" id="msg-text-${id}">${message}</div>
                </div>
            `;
            return messageDiv;
        }

        function appendMessage(messageData) {
            const messagesContainer = document.getElementById('chatMessages');
            if (!messagesContainer) {
                console.error('Messages container not found!');
                return;
            }
            const messageDiv = buildMessageElement(messageData);
            messagesContainer.appendChild(messageDiv);
            lastMessageId = Math.max(lastMessageId, parseInt(messageData.id) || 0);
            setTimeout(() => {
                scrollToBottom();
            }, 100);
        }

        function prependMessages(messages) {
            const messagesContainer = document.getElementById('chatMessages');
            if (!messagesContainer || !messages.length) return;
            const prevScrollHeight = messagesContainer.scrollHeight;
            const prevScrollTop = messagesContainer.scrollTop;
            const fragment = document.createDocumentFragment();
            let newOldest = oldestMessageId;
            messages.forEach((msg) => {
                if (renderedMessageIds.has(msg.id)) return;
                renderedMessageIds.add(msg.id);
                const messageDiv = buildMessageElement(msg);
                fragment.appendChild(messageDiv);
                const parsedId = parseInt(msg.id);
                if (!Number.isNaN(parsedId)) {
                    newOldest = newOldest === null ? parsedId : Math.min(newOldest, parsedId);
                }
            });
            messagesContainer.insertBefore(fragment, messagesContainer.firstChild);
            if (newOldest !== null) {
                oldestMessageId = newOldest;
            }
            const newScrollHeight = messagesContainer.scrollHeight;
            messagesContainer.scrollTop = newScrollHeight - prevScrollHeight + prevScrollTop;
        }

        function loadOlderMessages() {
            if (historyLoading || historyDone || !oldestMessageId) return;
            historyLoading = true;
            const encodedChatId = encodeURIComponent(chatId);
            const url = `/chat/${encodedChatId}/messages?source=db&before_id=${oldestMessageId}&per_page=${historyPerPage}`;
            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const list = Array.isArray(data.messages) ? data.messages : [];
                if (list.length === 0) {
                    historyDone = true;
                } else {
                    prependMessages(list);
                }
            })
            .catch(() => {
                historyLoading = false;
            })
            .finally(() => {
                historyLoading = false;
            });
        }

        const messagesContainerInit = document.getElementById('chatMessages');
        if (messagesContainerInit) {
            messagesContainerInit.addEventListener('scroll', () => {
                if (messagesContainerInit.scrollTop <= 40) {
                    loadOlderMessages();
                }
            });
        }

        function setOnlineStatus(isOnline) {
            const dot = document.getElementById('onlineDot');
            const text = document.getElementById('onlineText');
            if (dot) {
                dot.classList.toggle('online', !!isOnline);
            }
            if (text) {
                text.textContent = isOnline ? 'Online' : 'Offline';
            }
        }

        function setTypingStatus(isTyping) {
            const typing = document.getElementById('typingIndicator');
            if (typing) {
                typing.textContent = isTyping ? 'sedang mengetik...' : '';
            }
            const typingBar = document.getElementById('typingBar');
            if (typingBar) {
                typingBar.style.display = isTyping ? 'flex' : 'none';
            }
        }

        // Function to send message
        function sendChatMessage() {
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

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message, reply_to_message_id: currentReplyId })
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
                        is_read: data.message.is_read ?? false,
                        reply_to_message: data.message.reply_to_message ?? null,
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
                    clearReply();
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

        function setReply(messageId, senderName, messageText) {
            currentReplyId = messageId;
            currentReplySender = senderName;
            currentReplyText = messageText;
            const replyBar = document.getElementById('replyBar');
            const replyBarText = document.getElementById('replyBarText');
            if (replyBar && replyBarText) {
                replyBarText.textContent = `${senderName}: ${messageText}`;
                replyBar.style.display = 'flex';
            }
            const messageInput = document.getElementById('messageInput');
            if (messageInput) {
                messageInput.focus();
            }
        }

        function clearReply() {
            currentReplyId = null;
            currentReplySender = '';
            currentReplyText = '';
            const replyBar = document.getElementById('replyBar');
            const replyBarText = document.getElementById('replyBarText');
            if (replyBar && replyBarText) {
                replyBarText.textContent = '';
                replyBar.style.display = 'none';
            }
        }

        function openEditModal(id, content) {
            document.getElementById('editMessageId').value = id;
            document.getElementById('editMessageContent').value = content;
            document.getElementById('editModal').style.display = 'block';
            setTimeout(() => document.getElementById('editMessageContent').focus(), 100);
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }

        function submitEditMessage() {
            const id = document.getElementById('editMessageId').value;
            const content = document.getElementById('editMessageContent').value.trim();
            const chatId = '{{ $chat->id }}';
            
            if (!content) {
                alert('Pesan tidak boleh kosong');
                return;
            }

            const encodedChatId = encodeURIComponent(chatId);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/chat/${encodedChatId}/message/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const msgTextElement = document.getElementById(`msg-text-${id}`);
                    if (msgTextElement) {
                        msgTextElement.textContent = content;
                        const editBtn = msgTextElement.closest('.message-content').querySelector('.edit-btn');
                        if (editBtn) {
                             const safeContent = content.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                             editBtn.setAttribute('onclick', `openEditModal('${id}', '${safeContent}')`);
                        }
                    }
                    closeEditModal();
                } else {
                    alert(data.error || 'Gagal mengupdate pesan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate pesan');
            });
        }

        function deleteMessage(id) {
            const chatId = '{{ $chat->id }}';
            const encodedChatId = encodeURIComponent(chatId);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const doDelete = () => {
                fetch(`/chat/${encodedChatId}/message/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const msgElement = document.querySelector(`.message-item[data-id="${id}"]`) || 
                                         document.getElementById(`msg-text-${id}`).closest('.message-item');
                        if (msgElement) {
                            msgElement.remove();
                        }
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                text: 'Pesan berhasil dihapus',
                                confirmButtonColor: '#df2d24'
                            });
                        } else {
                            alert('Pesan berhasil dihapus');
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.error || 'Gagal menghapus pesan',
                                confirmButtonColor: '#df2d24'
                            });
                        } else {
                            alert(data.error || 'Gagal menghapus pesan');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghapus pesan',
                            confirmButtonColor: '#df2d24'
                        });
                    } else {
                        alert('Terjadi kesalahan saat menghapus pesan');
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus pesan?',
                    text: 'Pesan yang dihapus tidak bisa dikembalikan.',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#df2d24',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        doDelete();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
                    doDelete();
                }
            }
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

        function updateMessageUI(messageId, newText) {
            const msgTextElement = document.getElementById(`msg-text-${messageId}`);
            if (!msgTextElement) return;
            msgTextElement.textContent = newText;
            const editBtn = msgTextElement.closest('.message-content')?.querySelector('.edit-btn');
            if (editBtn) {
                const safeContent = newText.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                editBtn.setAttribute('onclick', `openEditModal('${messageId}', '${safeContent}')`);
            }
        }

        function removeMessageUI(messageId) {
            const msgElement = document.querySelector(`.message-item[data-id="${messageId}"]`) || 
                               document.getElementById(`msg-text-${messageId}`)?.closest('.message-item');
            if (msgElement) {
                msgElement.remove();
            }
        }

        function applyReadStatus(messageIds) {
            if (!Array.isArray(messageIds)) return;
            messageIds.forEach((id) => {
                const msgElement = document.querySelector(`.message-item[data-id="${id}"]`);
                if (!msgElement) return;
                if (!msgElement.classList.contains('message-sent')) return;
                const editBtn = msgElement.querySelector('.edit-btn');
                if (editBtn) editBtn.remove();
            });
        }

        if (typeof Echo !== 'undefined' && window.Echo) {
            console.log('🔌 Connecting to Pusher channel: chat.' + chatId);
            
            const channel = window.Echo.private(`chat.${chatId}`);
            const presenceChannel = window.Echo.join(`presence-chat.${chatId}`);

            presenceChannel.here((users) => {
                const otherOnline = users.some(u => u.id === otherUserId);
                setOnlineStatus(otherOnline);
            });

            presenceChannel.joining((user) => {
                if (user.id === otherUserId) {
                    setOnlineStatus(true);
                }
            });

            presenceChannel.leaving((user) => {
                if (user.id === otherUserId) {
                    setOnlineStatus(false);
                }
            });

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

                if (Number(e.message.sender_id) === Number(otherUserId)) {
                    setTypingStatus(false);
                }
                
                const msg = {
                    id: e.message.id,
                    sender_id: e.message.sender_id,
                    sender_name: e.message.sender_name || 'User',
                    message: e.message.message,
                    is_read: e.message.is_read ?? false,
                    reply_to_message: e.message.reply_to_message ?? null,
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
            
            // Also listen without dot prefix (fallback)
            channel.listen('MessageSent', (e) => {
                console.log('📨 Message received via Pusher (fallback):', e);
                console.log('📨 Event data:', JSON.stringify(e, null, 2));
                
                if (!e.message) {
                    console.error('❌ Invalid event data: message is missing');
                    return;
                }

                if (Number(e.message.sender_id) === Number(otherUserId)) {
                    setTypingStatus(false);
                }
                
                const msg = {
                    id: e.message.id,
                    sender_id: e.message.sender_id,
                    sender_name: e.message.sender_name || 'User',
                    message: e.message.message,
                    is_read: e.message.is_read ?? false,
                    reply_to_message: e.message.reply_to_message ?? null,
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

            channel.listen('.MessageUpdated', (e) => {
                if (!e.message) return;
                updateMessageUI(e.message.id, e.message.message);
            });

            channel.listen('MessageUpdated', (e) => {
                if (!e.message) return;
                updateMessageUI(e.message.id, e.message.message);
            });

            channel.listen('.MessageDeleted', (e) => {
                if (!e.message_id) return;
                removeMessageUI(e.message_id);
            });

            channel.listen('MessageDeleted', (e) => {
                if (!e.message_id) return;
                removeMessageUI(e.message_id);
            });

            channel.listen('.MessageRead', (e) => {
                if (!e.message_ids) return;
                applyReadStatus(e.message_ids);
            });

            channel.listen('MessageRead', (e) => {
                if (!e.message_ids) return;
                applyReadStatus(e.message_ids);
            });

            const messageInput = document.getElementById('messageInput');
            if (messageInput) {
                const sendTyping = (typing) => {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    if (!csrfToken) return;
                    const encodedChatId = encodeURIComponent(chatId);
                    const socketId = window.Echo?.socketId?.() || window.Echo?.connector?.pusher?.connection?.socket_id || '';
                    fetch(`/chat/${encodedChatId}/typing`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Socket-ID': socketId,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ typing: !!typing })
                    }).catch(() => {});
                };

                messageInput.addEventListener('input', () => {
                    const now = Date.now();
                    if (now - lastTypingSentAt > 600) {
                        lastTypingSentAt = now;
                        sendTyping(true);
                    }
                    clearTimeout(typingTimeout);
                    typingTimeout = setTimeout(() => {
                        sendTyping(false);
                    }, 1200);
                });

                messageInput.addEventListener('keydown', () => {
                    const now = Date.now();
                    if (now - lastTypingSentAt > 600) {
                        lastTypingSentAt = now;
                        sendTyping(true);
                    }
                });

                messageInput.addEventListener('blur', () => {
                    sendTyping(false);
                });
            }

            channel.listen('.TypingIndicator', (e) => {
                if (Number(e.user_id) === Number(otherUserId)) {
                    setTypingStatus(!!e.typing);
                }
            });

            channel.listen('TypingIndicator', (e) => {
                if (Number(e.user_id) === Number(otherUserId)) {
                    setTypingStatus(!!e.typing);
                }
            });
        } else {
            console.error('Laravel Echo is not loaded! Make sure Pusher credentials are set in .env');
        }

        // Initial scroll to bottom
        scrollToBottom();
    </script>
@endsection
