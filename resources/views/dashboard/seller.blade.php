@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-4">Dashboard Seller</h1>

<!-- Seller Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
            <div class="stat-card-icon blue animate-bounce-in">
                <i class="fas fa-car"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['my_total_cars'] ?? 0 }}</div>
            <div class="stat-card-label">Total Mobil Saya</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
            <div class="stat-card-icon yellow animate-bounce-in">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['pending_cars'] ?? 0 }}</div>
            <div class="stat-card-label">Menunggu Persetujuan</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.3s;">
            <div class="stat-card-icon green animate-bounce-in">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['approved_cars'] ?? 0 }}</div>
            <div class="stat-card-label">Mobil Disetujui</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.4s;">
            <div class="stat-card-icon red animate-bounce-in">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['rejected_cars'] ?? 0 }}</div>
            <div class="stat-card-label">Mobil Ditolak</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-slide-in-right">
            <div class="info-card-header">
                <h5 class="info-card-title">Aksi Cepat</h5>
            </div>
            <div class="d-grid gap-3" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                <a href="{{ route('cars.create') }}" class="btn btn-danger btn-animate" style="background: linear-gradient(135deg, #dc2626, #991b1b); border: none; padding: 14px 20px;">
                    <i class="fas fa-plus me-2"></i>Tambah Mobil Baru
                </a>
                <a href="{{ route('cars.index') }}" class="btn btn-outline-danger btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-list me-2"></i>Lihat Semua Mobil Saya
                </a>
                <a href="{{ route('chat.seller.index') }}" class="btn btn-outline-primary btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-comments me-2"></i>Obrolan
                    @php
                        $unreadChats = \App\Models\Chat::where('seller_id', Auth::id())
                            ->whereHas('messages', function($query) {
                                $query->where('sender_id', '!=', Auth::id())
                                      ->where('is_read', false);
                            })
                            ->count();
                    @endphp
                    @if($unreadChats > 0)
                        <span class="badge bg-danger ms-2">{{ $unreadChats }}</span>
                    @endif
                </a>
                <a href="{{ route('index') }}" class="btn btn-outline-dark btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-home me-2"></i>Kunjungi Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Chats Section -->
@php
    $recentChats = \App\Models\Chat::where('seller_id', Auth::id())
        ->with(['buyer', 'car', 'messages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->orderBy('last_message_at', 'desc')
        ->limit(5)
        ->get();
@endphp
@if($recentChats->count() > 0)
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-comments me-2"></i>Obrolan Terbaru
                </h5>
                <a href="{{ route('chat.seller.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="recent-chats-list">
                @foreach($recentChats as $chat)
                    @php
                        $otherUser = $chat->buyer;
                        $lastMessage = $chat->messages->first();
                        $unreadCount = $chat->getUnreadCount(Auth::id());
                    @endphp
                    <a href="{{ route('chat.show', $chat->id) }}" class="recent-chat-item {{ $unreadCount > 0 ? 'recent-chat-unread' : '' }}">
                        <div class="recent-chat-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="recent-chat-content">
                            <div class="recent-chat-header">
                                <span class="recent-chat-name">{{ $otherUser->name }}</span>
                                @if($lastMessage)
                                    <span class="recent-chat-time">{{ $lastMessage->created_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            <div class="recent-chat-preview">
                                @if($lastMessage)
                                    <span class="recent-chat-message">
                                        {{ Str::limit($lastMessage->message, 40) }}
                                    </span>
                                @else
                                    <span class="recent-chat-message text-muted">Belum ada pesan</span>
                                @endif
                                @if($unreadCount > 0)
                                    <span class="recent-chat-badge">{{ $unreadCount }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- Status Information -->
@if($stats['pending_cars'] > 0)
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="alert alert-warning animate-fade-in">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Mobil Menunggu Persetujuan</h5>
                    <p class="mb-0">Anda memiliki {{ $stats['pending_cars'] }} mobil yang sedang menunggu persetujuan dari admin. Silakan tunggu konfirmasi melalui email atau notifikasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Cars Section -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-car me-2"></i>Mobil Terbaru Saya
                </h5>
                <a href="{{ route('cars.index') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            @if($stats['recent_cars']->count() > 0)
            <div class="recent-cars-grid">
                @foreach($stats['recent_cars'] as $car)
                <div class="recent-car-card">
                    <div class="recent-car-image">
                        @if($car->image && is_array($car->image) && count($car->image) > 0)
                            <img src="{{ asset('storage/' . $car->image[0]) }}" alt="{{ $car->brand }}">
                        @else
                            <div class="car-placeholder">
                                <i class="fas fa-car"></i>
                            </div>
                        @endif
                        <span class="car-type-badge {{ $car->tipe == 'rent' ? 'badge-rent' : 'badge-sale' }}">
                            {{ $car->tipe == 'rent' ? 'Sewa' : 'Jual' }}
                        </span>
                        <span class="car-status-badge {{ $car->status == 'approved' ? 'badge-approved' : ($car->status == 'pending' ? 'badge-pending' : 'badge-rejected') }}">
                            @if($car->status == 'approved')
                                <i class="fas fa-check-circle"></i> Disetujui
                            @elseif($car->status == 'pending')
                                <i class="fas fa-clock"></i> Menunggu
                            @else
                                <i class="fas fa-times-circle"></i> Ditolak
                            @endif
                        </span>
                    </div>
                    <div class="recent-car-info">
                        <h6 class="recent-car-brand">{{ strtoupper($car->brand) }}</h6>
                        <p class="recent-car-details">
                            <i class="fas fa-calendar-alt"></i> {{ $car->tahun }} &nbsp;
                            <i class="fas fa-tachometer-alt"></i> {{ number_format($car->kilometer, 0, ',', '.') }} km
                        </p>
                        <p class="recent-car-price">Rp {{ number_format($car->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-car fa-3x mb-3"></i>
                    <p>Belum ada mobil yang Anda posting</p>
                    <a href="{{ route('cars.create') }}" class="btn btn-danger mt-3">
                        <i class="fas fa-plus me-2"></i>Post Mobil Pertama
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.recent-cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.recent-car-card {
    background: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s;
    cursor: pointer;
    position: relative;
}

.recent-car-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(220, 38, 38, 0.15);
    border-color: #dc2626;
}

.recent-car-image {
    position: relative;
    width: 100%;
    height: 140px;
    overflow: hidden;
    background: linear-gradient(135deg, #f5f5f5, #e9ecef);
}

.recent-car-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.recent-car-card:hover .recent-car-image img {
    transform: scale(1.1);
}

.car-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.car-placeholder i {
    font-size: 48px;
}

.car-type-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    padding: 4px 10px;
    border-radius: 5px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
}

.badge-sale {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: #fff;
}

.badge-rent {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: #fff;
}

.car-status-badge {
    position: absolute;
    bottom: 8px;
    left: 8px;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 9px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.badge-approved {
    background: rgba(16, 185, 129, 0.9);
    color: #fff;
}

.badge-pending {
    background: rgba(245, 158, 11, 0.9);
    color: #fff;
}

.badge-rejected {
    background: rgba(239, 68, 68, 0.9);
    color: #fff;
}

.recent-car-info {
    padding: 14px;
}

.recent-car-brand {
    font-size: 16px;
    font-weight: 800;
    color: #1a1a1a;
    margin: 0 0 8px 0;
    text-transform: uppercase;
}

.recent-car-details {
    font-size: 12px;
    color: #6b7280;
    margin: 0 0 8px 0;
}

.recent-car-details i {
    margin-right: 4px;
    color: #dc2626;
}

.recent-car-price {
    font-size: 16px;
    font-weight: 900;
    color: #dc2626;
    margin: 0;
}

.info-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

/* Stat Card Icon Colors for Seller Dashboard */
.stat-card-icon.blue {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%) !important;
    color: #fff !important;
}

.stat-card-icon.yellow {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%) !important;
    color: #fff !important;
}

.stat-card-icon.green {
    background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%) !important;
    color: #fff !important;
}

.stat-card-icon.red {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%) !important;
    color: #fff !important;
}

.stat-card-icon {
    width: 70px !important;
    height: 70px !important;
    border-radius: 5px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-bottom: 20px !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    transition: all 0.3s !important;
}

.stat-card-icon i {
    font-size: 32px !important;
    color: #fff !important;
    display: block !important;
}

.stat-card:hover .stat-card-icon {
    transform: scale(1.1) rotate(5deg) !important;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2) !important;
}

/* Recent Chats Styles */
.recent-chats-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.recent-chat-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}

.recent-chat-item:hover {
    background: #f8f9fa;
    text-decoration: none;
    color: inherit;
}

.recent-chat-item:last-child {
    border-bottom: none;
}

.recent-chat-unread {
    background: #f0f7ff;
    font-weight: 600;
}

.recent-chat-unread:hover {
    background: #e6f2ff;
}

.recent-chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    margin-right: 12px;
    flex-shrink: 0;
}

.recent-chat-content {
    flex: 1;
    min-width: 0;
}

.recent-chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.recent-chat-name {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
}

.recent-chat-time {
    font-size: 11px;
    color: #6c757d;
    white-space: nowrap;
    margin-left: 8px;
}

.recent-chat-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.recent-chat-message {
    font-size: 12px;
    color: #6c757d;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
}

.recent-chat-unread .recent-chat-message {
    color: #1a1a1a;
    font-weight: 600;
}

.recent-chat-badge {
    background: #df2d24;
    color: #fff;
    border-radius: 5px;
    padding: 2px 6px;
    font-size: 10px;
    font-weight: 700;
    min-width: 18px;
    text-align: center;
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .recent-cars-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }

    .recent-chat-item {
        padding: 10px 12px;
    }

    .recent-chat-avatar {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
}
</style>

@push('scripts')
<script>
// Animation for count up
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.animate-count-up');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 30);
    });
});
</script>
@endpush
@endsection
