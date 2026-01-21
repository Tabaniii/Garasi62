@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title mb-0">Obrolan</h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="chat-search-container mb-3">
                <div class="chat-search-wrapper">
                    <i class="fas fa-search chat-search-icon"></i>
                    <input type="text" id="chatSearch" class="chat-search-input" placeholder="Cari obrolan...">
                </div>
            </div>

            @php
                $shortcuts = $chats->groupBy('buyer_id');
            @endphp

            @if($shortcuts->count() > 0)
            <div class="chat-shortcut-container mb-3">
                @foreach($shortcuts as $buyerId => $buyerChats)
                    @php
                        $firstChat = $buyerChats->first();
                        $buyer = $firstChat->buyer ?? null;
                        if (!$buyer) continue;
                        $unread = $buyerChats->sum(fn($c) => $c->unread_count ?? 0);
                    @endphp
                    <a href="{{ route('chat.show', $firstChat->id) }}" class="chat-shortcut-item" title="Chat dengan {{ $buyer->name }}">
                        <div class="shortcut-avatar">
                            <span>{{ strtoupper(substr($buyer->name,0,1)) }}</span>
                        </div>
                        <div class="shortcut-name">{{ Str::limit($buyer->name, 10) }}</div>
                        @if($unread > 0)
                            <span class="shortcut-badge">{{ $unread }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
            @endif

            <!-- Chat List -->
            <div class="chat-list-container">
                @if($chats->count() > 0)
                    @foreach($chats as $chat)
                        @php
                            $otherUser = $chat->buyer ?? null;
                            if (!$otherUser) continue;
                            $lastMessage = $chat->last_message ?? null;
                            $unreadCount = $chat->unread_count ?? 0;
                        @endphp
                        <a href="{{ route('chat.show', $chat->id) }}" class="chat-item {{ $unreadCount > 0 ? 'chat-item-unread' : '' }}">
                            <div class="chat-item-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="chat-item-content">
                                <div class="chat-item-header">
                                    <span class="chat-item-name">{{ $otherUser->name }}</span>
                                    @if($lastMessage)
                                        <span class="chat-item-time">{{ $lastMessage->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                <div class="chat-item-preview">
                                    @if($lastMessage)
                                        <span class="chat-item-message">
                                            {{ Str::limit($lastMessage->message, 50) }}
                                        </span>
                                    @else
                                        <span class="chat-item-message text-muted">Belum ada pesan</span>
                                    @endif
                                    @if($unreadCount > 0)
                                        <span class="chat-item-badge">{{ $unreadCount }}</span>
                                    @endif
                                </div>
                                @if($chat->car)
                                <div class="chat-item-car">
                                    <i class="fas fa-car"></i> {{ $chat->car->brand }} {{ $chat->car->nama ?? '' }}
                                </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="chat-empty-state">
                        <i class="fas fa-comments fa-3x mb-3 text-muted"></i>
                        <p class="text-muted">Belum ada obrolan</p>
                        <small class="text-muted">Obrolan akan muncul ketika buyer menghubungi Anda</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.chat-search-container {
    position: sticky;
    top: 0;
    z-index: 10;
    background: #fff;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.chat-search-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.chat-search-icon {
    position: absolute;
    left: 16px;
    color: #6c757d;
    font-size: 14px;
}

.chat-search-input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    font-size: 14px;
    outline: none;
    transition: all 0.3s;
}

.chat-search-input:focus {
    border-color: #df2d24;
    box-shadow: 0 0 0 3px rgba(223, 45, 36, 0.1);
}

.chat-list-container {
    background: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.chat-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
    position: relative;
}

.chat-item:hover {
    background: #f8f9fa;
    text-decoration: none;
    color: inherit;
}

.chat-item-unread {
    background: #f0f7ff;
    font-weight: 600;
}

.chat-item-unread:hover {
    background: #e6f2ff;
}

.chat-item-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #df2d24, #b91c1c);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-right: 12px;
    flex-shrink: 0;
}

.chat-item-content {
    flex: 1;
    min-width: 0;
}

.chat-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.chat-item-name {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a1a;
}

.chat-item-time {
    font-size: 12px;
    color: #6c757d;
    white-space: nowrap;
    margin-left: 8px;
}

.chat-item-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.chat-item-message {
    font-size: 13px;
    color: #6c757d;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
}

.chat-item-unread .chat-item-message {
    color: #1a1a1a;
    font-weight: 600;
}

.chat-item-badge {
    background: #df2d24;
    color: #fff;
    border-radius: 5px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 700;
    min-width: 20px;
    text-align: center;
    flex-shrink: 0;
}

.chat-item-car {
    font-size: 11px;
    color: #6c757d;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.chat-empty-state {
    text-align: center;
    padding: 60px 20px;
}

@media (max-width: 768px) {
    .chat-item {
        padding: 10px 12px;
    }

    .chat-item-avatar {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }

    .chat-item-name {
        font-size: 14px;
    }

    .chat-item-message {
        font-size: 12px;
    }
}

.chat-shortcut-container{
    display:flex;
    gap:12px;
    overflow-x:auto;
    padding:8px 4px 12px;
}
.chat-shortcut-item{
    position:relative;
    text-align:center;
    min-width:72px;
    text-decoration:none;
    color:#1a1a1a;
}
.shortcut-avatar{
    width:52px;
    height:52px;
    border-radius:50%;
    background:linear-gradient(135deg,#df2d24,#b91c1c);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    margin:0 auto 6px;
    box-shadow:0 4px 12px rgba(223,45,36,0.25);
}
.shortcut-name{
    font-size:12px;
    white-space:nowrap;
}
.shortcut-badge{
    position:absolute;
    top:-4px;
    right:12px;
    background:#df2d24;
    color:#fff;
    border-radius:10px;
    padding:2px 6px;
    font-size:10px;
    font-weight:700;
}
</style>
</style>

<script>
// Search functionality
document.getElementById('chatSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const chatItems = document.querySelectorAll('.chat-item');
    
    chatItems.forEach(item => {
        const name = item.querySelector('.chat-item-name').textContent.toLowerCase();
        const message = item.querySelector('.chat-item-message').textContent.toLowerCase();
        const car = item.querySelector('.chat-item-car')?.textContent.toLowerCase() || '';
        
        if (name.includes(searchTerm) || message.includes(searchTerm) || car.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
@endsection

