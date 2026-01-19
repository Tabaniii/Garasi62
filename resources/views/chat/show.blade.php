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
                            @foreach($messages as $message)
                                <div class="message-item {{ $message->sender_id === Auth::id() ? 'message-sent' : 'message-received' }}">
                                    <div class="message-content">
                                        <div class="message-header">
                                            <span class="message-sender">{{ $message->sender->name }}</span>
                                            <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="message-text">{{ $message->message }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Chat Input -->
                        <div class="chat-input-container">
                            <form id="chatForm" class="chat-form">
                                @csrf
                                <div class="chat-input-wrapper">
                                    <input type="text" id="messageInput" class="chat-input" placeholder="Ketik pesan..." autocomplete="off">
                                    <button type="submit" class="chat-send-btn">
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
    </style>

    <script>
        const chatId = {{ $chat->id }};
        const currentUserId = {{ Auth::id() }};
        let lastMessageId = {{ $messages->count() > 0 ? $messages->last()->id : 0 }};

        // Auto scroll to bottom
        function scrollToBottom() {
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Load messages
        function loadMessages() {
            fetch(`{{ route('chat.messages', $chat->id) }}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const messagesContainer = document.getElementById('chatMessages');
                const currentScroll = messagesContainer.scrollTop;
                const isScrolledToBottom = messagesContainer.scrollHeight - messagesContainer.clientHeight <= currentScroll + 100;

                // Check if there are new messages
                const newMessages = data.messages.filter(msg => msg.id > lastMessageId);
                
                if (newMessages.length > 0) {
                    newMessages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `message-item ${message.sender_id === currentUserId ? 'message-sent' : 'message-received'}`;
                        messageDiv.innerHTML = `
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-sender">${message.sender.name}</span>
                                    <span class="message-time">${new Date(message.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</span>
                                </div>
                                <div class="message-text">${message.message}</div>
                            </div>
                        `;
                        messagesContainer.appendChild(messageDiv);
                    });

                    lastMessageId = data.messages[data.messages.length - 1].id;

                    if (isScrolledToBottom) {
                        scrollToBottom();
                    }
                }
            })
            .catch(error => console.error('Error loading messages:', error));
        }

        // Send message
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message) return;

            const sendBtn = document.querySelector('.chat-send-btn');
            const originalHTML = sendBtn.innerHTML;
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            fetch(`{{ route('chat.store', $chat->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageInput.value = '';
                    
                    // Add message to chat
                    const messagesContainer = document.getElementById('chatMessages');
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message-item message-sent';
                    messageDiv.innerHTML = `
                        <div class="message-content">
                            <div class="message-header">
                                <span class="message-sender">${data.message.sender.name}</span>
                                <span class="message-time">${new Date(data.message.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</span>
                            </div>
                            <div class="message-text">${data.message.message}</div>
                        </div>
                    `;
                    messagesContainer.appendChild(messageDiv);
                    scrollToBottom();
                    lastMessageId = data.message.id;
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal mengirim pesan. Silakan coba lagi.',
                    confirmButtonColor: '#df2d24'
                });
            })
            .finally(() => {
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalHTML;
            });
        });

        // Poll for new messages every 2 seconds
        setInterval(loadMessages, 2000);

        // Initial scroll to bottom
        scrollToBottom();
    </script>
@endsection

