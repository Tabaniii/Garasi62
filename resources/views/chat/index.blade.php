@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title mb-0">Obrolan</h1>
                <div class="d-flex gap-2">
                    <button id="selectModeBtn" class="btn btn-sm btn-outline-primary" onclick="toggleSelectMode()">
                        <i class="fas fa-check-square"></i> Pilih
                    </button>
                    <button id="selectAllBtn" class="btn btn-sm btn-outline-primary" onclick="toggleSelectAll()" style="display: none;">
                        <i class="fas fa-check-square"></i> Pilih Semua
                    </button>
                    <button id="deleteSelectedBtn" class="btn btn-sm btn-danger" onclick="deleteSelectedChats()" style="display: none;">
                        <i class="fas fa-trash"></i> Hapus Terpilih
                    </button>
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
                // Group chats by seller_id safely
                $shortcuts = collect([]);
                if ($chats && $chats->count() > 0) {
                    $shortcuts = $chats->groupBy(function($chat) {
                        return $chat->seller_id ?? null;
                    })->filter(function($group, $key) {
                        return $key !== null;
                    });
                }
            @endphp

            @if($shortcuts->count() > 0)
            <div class="chat-shortcut-container mb-3">
                @foreach($shortcuts as $sellerId => $sellerChats)
                    @php
                        $firstChat = $sellerChats->first();
                        if (!$firstChat) continue;
                        
                        // Use other_user if available, otherwise fallback to seller
                        $seller = $firstChat->other_user ?? $firstChat->seller ?? null;
                        
                        // Double check: make sure we're not showing buyer's own name
                        if ($seller && $seller->id == Auth::id()) {
                            $seller = $firstChat->seller ?? null;
                        }
                        
                        if (!$seller) continue;
                        
                        // Calculate unread count safely
                        $unread = 0;
                        foreach ($sellerChats as $chat) {
                            if (isset($chat->unread_count)) {
                                $unread += (int)$chat->unread_count;
                            }
                        }
                    @endphp
                    <a href="{{ route('chat.show', $firstChat->id) }}" class="chat-shortcut-item" title="Chat dengan {{ $seller->name }}" onclick="loadChat(event, '{{ $firstChat->id }}')">
                        <div class="shortcut-avatar">
                            <span>{{ strtoupper(substr($seller->name,0,1)) }}</span>
                        </div>
                        <div class="shortcut-name">{{ Str::limit($seller->name, 10) }}</div>
                        @if($unread > 0)
                            <span class="shortcut-badge">{{ $unread }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
            @endif

            <!-- Chat List -->
            <div class="chat-list-container">
                @if($chats && $chats->count() > 0)
                    @foreach($chats as $chat)
                        @php
                            // Use other_user if available (set in ChatController), otherwise fallback to seller
                            $otherUser = $chat->other_user ?? $chat->seller ?? null;
                            
                            // Double check: make sure we're not showing buyer's own name
                            if ($otherUser && $otherUser->id == Auth::id()) {
                                // Wrong user, try seller
                                $otherUser = $chat->seller ?? null;
                            }
                            
                            if (!$otherUser) continue;
                            
                            // Get last message safely
                            $lastMessage = null;
                            if (isset($chat->last_message)) {
                                if (is_object($chat->last_message)) {
                                    $lastMessage = $chat->last_message;
                                } elseif (is_string($chat->last_message)) {
                                    // If last_message is just a string, create object
                                    $lastMessage = (object)[
                                        'message' => $chat->last_message,
                                        'created_at' => $chat->last_message->created_at ?? now()
                                    ];
                                }
                            }
                            
                            // Get unread count safely
                            $unreadCount = 0;
                            if (isset($chat->unread_count)) {
                                $unreadCount = (int)$chat->unread_count;
                            }
                        @endphp
                        <div class="chat-item-wrapper">
                            <input type="checkbox" class="chat-checkbox" value="{{ $chat->id }}" onchange="updateDeleteButton()" style="display: none;">
                            <a href="{{ route('chat.show', $chat->id) }}" class="chat-item {{ $unreadCount > 0 ? 'chat-item-unread' : '' }}" onclick="return !event.ctrlKey && !event.metaKey;">
                                <div class="chat-item-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="chat-item-content">
                        <a href="{{ route('chat.show', $chat->id) }}" class="chat-item {{ $unreadCount > 0 ? 'chat-item-unread' : '' }}" data-chat-id="{{ $chat->id }}" onclick="loadChat(event, '{{ $chat->id }}')">
                            <div class="chat-item-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="chat-item-content">
                                <div class="chat-item-header">
                                    <span class="chat-item-name">{{ $otherUser->name }}</span>
                                    @if($lastMessage && isset($lastMessage->created_at))
                                        @php
                                            try {
                                                $lastMessageTime = is_object($lastMessage->created_at) 
                                                    ? $lastMessage->created_at 
                                                    : \Carbon\Carbon::parse($lastMessage->created_at);
                                            } catch (\Exception $e) {
                                                $lastMessageTime = null;
                                            }
                                        @endphp
                                        @if($lastMessageTime)
                                            <span class="chat-item-time">{{ $lastMessageTime->diffForHumans() }}</span>
                                        @endif
                                    @endif
                                </div>
                                <div class="chat-item-preview">
                                    @if($lastMessage && isset($lastMessage->message) && $lastMessage->message)
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
                                @if(isset($chat->car) && $chat->car)
                                <div class="chat-item-car">
                                    <i class="fas fa-car"></i> {{ $chat->car->brand ?? '' }} {{ $chat->car->nama ?? '' }}
                                </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="chat-empty-state">
                        <i class="fas fa-comments fa-3x mb-3 text-muted"></i>
                        <p class="text-muted">Belum ada obrolan</p>
                        <small class="text-muted">Mulai obrolan dengan penjual dari halaman detail mobil</small>
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

.chat-item-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.chat-checkbox {
    position: absolute;
    left: 15px;
    z-index: 10;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.chat-item-wrapper.select-mode .chat-checkbox {
    display: block !important;
}

.chat-item-wrapper.select-mode .chat-item {
    padding-left: 45px;
}

.chat-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
    width: 100%;
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
// Load chat without page reload
function loadChat(event, chatId) {
    event.preventDefault();
    
    // Update URL without reload
    if (window.history && window.history.pushState) {
        window.history.pushState({chatId: chatId}, '', '{{ route('chat.show', '') }}/' + chatId);
    }
    
    // Load chat content via AJAX
    fetch('{{ route('chat.show', '') }}/' + chatId, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.text())
    .then(response => response.json())
    .then(data => {
        if (data.success && data.html) {
            // Replace main content with chat view
            document.querySelector('.main-content').innerHTML = data.html;
            
            // Re-execute scripts in the loaded content
            const scripts = document.querySelectorAll('.main-content script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
            
            // Mark messages as read
            setTimeout(() => {
                if (typeof markMessagesAsRead === 'function') {
                    markMessagesAsRead();
                }
                if (typeof initChatScripts === 'function') {
                    initChatScripts();
                }
            }, 100);
        } else {
            // Fallback to normal navigation
            window.location.href = '{{ route('chat.show', '') }}/' + chatId;
        }
    })
    .catch(error => {
        console.error('Error loading chat:', error);
        // Fallback to normal navigation
        window.location.href = '{{ route('chat.show', '') }}/' + chatId;
    });
}

// Refresh chat list without reload
function refreshChatList() {
    fetch('{{ route('chat.index') }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.text())
    .then(html => {
        // Update chat list container
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newChatList = doc.querySelector('.chat-list-container');
        const newShortcuts = doc.querySelector('.chat-shortcut-container');
        
        if (newChatList) {
            document.querySelector('.chat-list-container').innerHTML = newChatList.innerHTML;
        }
        if (newShortcuts) {
            const shortcutsContainer = document.querySelector('.chat-shortcut-container');
            if (shortcutsContainer) {
                shortcutsContainer.innerHTML = newShortcuts.innerHTML;
            }
        }
        
        // Re-attach event listeners
        attachChatItemListeners();
    })
    .catch(error => {
        console.error('Error refreshing chat list:', error);
    });
}

// Attach event listeners to chat items
function attachChatItemListeners() {
    document.querySelectorAll('.chat-item[data-chat-id]').forEach(item => {
        const chatId = item.getAttribute('data-chat-id');
        item.onclick = (e) => loadChat(e, chatId);
    });
    
    document.querySelectorAll('.chat-shortcut-item').forEach(item => {
        const href = item.getAttribute('href');
        if (href) {
            const chatId = href.split('/').pop();
            item.onclick = (e) => loadChat(e, chatId);
        }
    });
}

// Search functionality
document.getElementById('chatSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const chatItems = document.querySelectorAll('.chat-item');
    
    chatItems.forEach(item => {
        const name = item.querySelector('.chat-item-name').textContent.toLowerCase();
        const message = item.querySelector('.chat-item-message').textContent.toLowerCase();
        const car = item.querySelector('.chat-item-car')?.textContent.toLowerCase() || '';
        
        if (name.includes(searchTerm) || message.includes(searchTerm) || car.includes(searchTerm)) {
            item.closest('.chat-item-wrapper').style.display = 'flex';
        } else {
            item.closest('.chat-item-wrapper').style.display = 'none';
        }
    });
});

// Selection mode
let isSelectMode = false;

function toggleSelectMode() {
    isSelectMode = !isSelectMode;
    const wrappers = document.querySelectorAll('.chat-item-wrapper');
    const selectModeBtn = document.getElementById('selectModeBtn');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    
    if (isSelectMode) {
        wrappers.forEach(wrapper => wrapper.classList.add('select-mode'));
        selectAllBtn.style.display = 'inline-block';
        selectModeBtn.innerHTML = '<i class="fas fa-times"></i> Batal';
        selectModeBtn.classList.remove('btn-outline-primary');
        selectModeBtn.classList.add('btn-outline-secondary');
        updateDeleteButton();
    } else {
        wrappers.forEach(wrapper => {
            wrapper.classList.remove('select-mode');
            const checkbox = wrapper.querySelector('.chat-checkbox');
            if (checkbox) checkbox.checked = false;
        });
        selectAllBtn.style.display = 'none';
        deleteBtn.style.display = 'none';
        selectModeBtn.innerHTML = '<i class="fas fa-check-square"></i> Pilih';
        selectModeBtn.classList.remove('btn-outline-secondary');
        selectModeBtn.classList.add('btn-outline-primary');
    }
}

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.chat-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
    
    updateDeleteButton();
}

function updateDeleteButton() {
    const checkboxes = document.querySelectorAll('.chat-checkbox:checked');
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    
    if (checkboxes.length > 0) {
        deleteBtn.style.display = 'inline-block';
        deleteBtn.innerHTML = `<i class="fas fa-trash"></i> Hapus (${checkboxes.length})`;
    } else {
        deleteBtn.style.display = 'none';
    }
}

function deleteSelectedChats() {
    const checkboxes = document.querySelectorAll('.chat-checkbox:checked');
    const chatIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (chatIds.length === 0) {
        alert('Pilih obrolan yang ingin dihapus');
        return;
    }
    
    if (!confirm(`Apakah Anda yakin ingin menghapus ${chatIds.length} obrolan?`)) {
        return;
    }
    
    // Show loading
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const originalHTML = deleteBtn.innerHTML;
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
    
    fetch('{{ route("chat.destroy") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                           document.querySelector('input[name="_token"]')?.value || '',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ chat_ids: chatIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove deleted chats from UI
            chatIds.forEach(chatId => {
                const wrapper = document.querySelector(`.chat-checkbox[value="${chatId}"]`)?.closest('.chat-item-wrapper');
                if (wrapper) {
                    wrapper.style.transition = 'opacity 0.3s';
                    wrapper.style.opacity = '0';
                    setTimeout(() => wrapper.remove(), 300);
                }
            });
            
            // Reset selection mode
            toggleSelectMode();
            
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || `Berhasil menghapus ${data.deleted_count} obrolan`,
                    confirmButtonColor: '#df2d24',
                    timer: 2000
                });
            } else {
                alert(data.message || `Berhasil menghapus ${data.deleted_count} obrolan`);
            }
        } else {
            throw new Error(data.error || 'Gagal menghapus obrolan');
        }
    })
    .catch(error => {
        console.error('Error deleting chats:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Gagal menghapus obrolan',
                confirmButtonColor: '#df2d24'
            });
        } else {
            alert('Gagal menghapus obrolan: ' + error.message);
        }
    })
    .finally(() => {
        deleteBtn.disabled = false;
        deleteBtn.innerHTML = originalHTML;
    });
}

// Enable selection mode with Ctrl/Cmd + Click or long press
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
        e.preventDefault();
        if (!isSelectMode) {
            toggleSelectMode();
        }
    }
});

// Right click to enable selection mode
document.addEventListener('contextmenu', function(e) {
    if (e.target.closest('.chat-item-wrapper')) {
        e.preventDefault();
        if (!isSelectMode) {
            toggleSelectMode();
        }
    }
});

// Long press on mobile
let longPressTimer;
document.addEventListener('touchstart', function(e) {
    if (e.target.closest('.chat-item-wrapper')) {
        longPressTimer = setTimeout(() => {
            if (!isSelectMode) {
                toggleSelectMode();
            }
        }, 500);
    }
});

document.addEventListener('touchend', function() {
    clearTimeout(longPressTimer);
});

// Click on chat item to toggle selection in select mode
document.addEventListener('click', function(e) {
    if (isSelectMode && e.target.closest('.chat-item')) {
        e.preventDefault();
        const wrapper = e.target.closest('.chat-item-wrapper');
        const checkbox = wrapper?.querySelector('.chat-checkbox');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            updateDeleteButton();
        }
    }
// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    attachChatItemListeners();
    
    // Listen for browser back/forward
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.chatId) {
            loadChat({preventDefault: () => {}}, event.state.chatId);
        } else {
            window.location.reload();
        }
    });
});
</script>
@endsection

