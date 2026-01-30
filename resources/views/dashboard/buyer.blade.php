@extends('layouts.admin')

@section('content')
@include('components.messages-widget')
<h1 class="page-title mb-4">Dashboard Buyer</h1>

<!-- Buyer Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
            <div class="stat-card-icon red animate-bounce-in">
                <i class="fas fa-car"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['available_cars'] ?? 0 }}</div>
            <div class="stat-card-label">Mobil Tersedia</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
            <div class="stat-card-icon green animate-bounce-in">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['cars_for_sale'] ?? 0 }}</div>
            <div class="stat-card-label">Mobil Dijual</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.3s;">
            <div class="stat-card-icon blue animate-bounce-in">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['cars_for_rent'] ?? 0 }}</div>
            <div class="stat-card-label">Mobil Disewa</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.4s;">
            <div class="stat-card-icon purple animate-bounce-in">
                <i class="fas fa-heart"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['wishlist_count'] ?? 0 }}</div>
            <div class="stat-card-label">Mobil Yang Kamu Mau</div>
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
                <a href="{{ route('cars') }}" class="btn btn-danger btn-animate" style="background: linear-gradient(135deg, #dc2626, #991b1b); border: none; padding: 14px 20px;">
                    <i class="fas fa-search me-2"></i>Cari Mobil
                </a>
                <a href="{{ route('cars') }}?tipe=buy" class="btn btn-outline-success btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-shopping-cart me-2"></i>Lihat Mobil Dijual
                </a>
                <a href="{{ route('cars') }}?tipe=rent" class="btn btn-outline-primary btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-calendar-alt me-2"></i>Lihat Mobil Disewa
                </a>
                <a href="{{ route('reports.my-reports') }}" class="btn btn-outline-warning btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-flag me-2"></i>Laporan Saya
                </a>
                <a href="{{ route('index') }}" class="btn btn-outline-dark btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-home me-2"></i>Kunjungi Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Available Cars Section -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-car me-2"></i>Mobil Tersedia Terbaru
                </h5>
                <a href="{{ route('cars') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            @if($stats['recent_cars']->count() > 0)
            <div class="recent-cars-grid">
                @foreach($stats['recent_cars'] as $car)
                <div class="recent-car-card" onclick="window.location.href='{{ route('car.details', $car->id) }}'">
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
                    </div>
                    <div class="recent-car-info">
                        <h6 class="recent-car-brand">{{ strtoupper($car->brand) }} {{ $car->nama }}</h6>
                        <p class="recent-car-details">
                            <i class="fas fa-calendar-alt"></i> {{ $car->tahun }} &nbsp;
                            <i class="fas fa-tachometer-alt"></i> {{ number_format($car->kilometer, 0, ',', '.') }} km &nbsp;
                            <i class="fas fa-cogs"></i> {{ $car->transmisi }}
                        </p>
                        <p class="recent-car-price">
                            Rp {{ number_format($car->harga, 0, ',', '.') }}
                            @if($car->tipe == 'rent')
                                <small class="text-muted">/hari</small>
                            @endif
                        </p>
                        @if($car->location)
                        <p class="recent-car-location">
                            <i class="fas fa-map-marker-alt"></i> {{ $car->location }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-car fa-3x mb-3"></i>
                    <p>Belum ada mobil yang tersedia saat ini</p>
                    <a href="{{ route('index') }}" class="btn btn-danger mt-3">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Wishlist Section -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-heart me-2"></i>Mobil Yang Kamu Mau
                </h5>
                <span class="badge bg-danger">{{ $stats['wishlist_count'] ?? 0 }} Mobil</span>
            </div>
            @if(isset($stats['wishlist_cars']) && $stats['wishlist_cars']->count() > 0)
            <div class="recent-cars-grid">
                @foreach($stats['wishlist_cars'] as $car)
                <div class="recent-car-card">
                    <div class="recent-car-image" onclick="window.location.href='{{ route('car.details', $car->id) }}'">
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
                        <form action="{{ route('wishlist.destroy', $car->id) }}" method="POST" class="wishlist-remove-form" onclick="event.stopPropagation();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="wishlist-remove-btn" title="Hapus dari wishlist">
                                <i class="fas fa-heart-broken"></i>
                            </button>
                        </form>
                    </div>
                    <div class="recent-car-info">
                        <h6 class="recent-car-brand" onclick="window.location.href='{{ route('car.details', $car->id) }}'">{{ strtoupper($car->brand) }} {{ $car->nama }}</h6>
                        <p class="recent-car-details">
                            <i class="fas fa-calendar-alt"></i> {{ $car->tahun }} &nbsp;
                            <i class="fas fa-tachometer-alt"></i> {{ number_format($car->kilometer, 0, ',', '.') }} km &nbsp;
                            <i class="fas fa-cogs"></i> {{ $car->transmisi }}
                        </p>
                        <p class="recent-car-price">
                            Rp {{ number_format($car->harga, 0, ',', '.') }}
                            @if($car->tipe == 'rent')
                                <small class="text-muted">/hari</small>
                            @endif
                        </p>
                        @if($car->location)
                        <p class="recent-car-location">
                            <i class="fas fa-map-marker-alt"></i> {{ $car->location }}
                        </p>
                        @endif
                        <div class="wishlist-actions">
                            <a href="{{ route('car.details', $car->id) }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-heart fa-3x mb-3" style="opacity: 0.3;"></i>
                    <h5>Belum ada mobil di wishlist</h5>
                    <p>Tambahkan mobil ke wishlist dengan mengklik ikon hati di halaman detail mobil</p>
                    <a href="{{ route('cars') }}" class="btn btn-danger mt-3">
                        <i class="fas fa-search me-2"></i>Cari Mobil
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-th-large me-2"></i>Kategori Mobil
                </h5>
            </div>
            <div class="categories-grid">
                <a href="{{ route('cars') }}?tipe=buy" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="category-info">
                        <h6>Mobil Dijual</h6>
                        <p>{{ $stats['cars_for_sale'] ?? 0 }} mobil tersedia</p>
                    </div>
                </a>
                <a href="{{ route('cars') }}?tipe=rent" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="category-info">
                        <h6>Mobil Disewa</h6>
                        <p>{{ $stats['cars_for_rent'] ?? 0 }} mobil tersedia</p>
                    </div>
                </a>
                <a href="{{ route('cars') }}" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="category-info">
                        <h6>Semua Mobil</h6>
                        <p>{{ $stats['available_cars'] ?? 0 }} mobil tersedia</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.recent-cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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
}

.recent-car-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(220, 38, 38, 0.15);
    border-color: #dc2626;
}

.recent-car-image {
    position: relative;
    width: 100%;
    height: 180px;
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
    transform: scale(1.05);
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
    top: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    z-index: 2;
}

.badge-sale {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: #fff;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.badge-rent {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: #fff;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.recent-car-info {
    padding: 16px;
}

.recent-car-brand {
    font-size: 16px;
    font-weight: 800;
    color: #1a1a1a;
    margin: 0 0 8px 0;
    text-transform: uppercase;
    line-height: 1.3;
}

.recent-car-details {
    font-size: 12px;
    color: #6b7280;
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.recent-car-details i {
    margin-right: 4px;
    color: #dc2626;
}

.recent-car-price {
    font-size: 18px;
    font-weight: 900;
    color: #dc2626;
    margin: 0 0 8px 0;
}

.recent-car-location {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
}

.recent-car-location i {
    margin-right: 4px;
    color: #dc2626;
}

.wishlist-remove-form {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 3;
}

.wishlist-remove-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(220, 38, 38, 0.9);
    border: none;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.wishlist-remove-btn:hover {
    background: rgba(220, 38, 38, 1);
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
}

.wishlist-remove-btn i {
    font-size: 16px;
}

.wishlist-actions {
    margin-top: 12px;
    display: flex;
    gap: 8px;
}

.recent-car-card {
    position: relative;
}

.info-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

/* Stat Card Icon Colors for Buyer Dashboard */
.stat-card-icon.red {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%) !important;
    color: #fff !important;
}

.stat-card-icon.green {
    background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%) !important;
    color: #fff !important;
}

.stat-card-icon.blue {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%) !important;
    color: #fff !important;
}

.stat-card-icon.purple {
    background: linear-gradient(135deg, #a855f7 0%, #9333ea 50%, #7e22ce 100%) !important;
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

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.category-card {
    background: #fff;
    border-radius: 5px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 16px;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 38, 38, 0.15);
    border-color: #dc2626;
    text-decoration: none;
    color: inherit;
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 5px;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    flex-shrink: 0;
}

.category-info h6 {
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 4px 0;
}

.category-info p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

@media (max-width: 768px) {
    .recent-cars-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .categories-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .category-card {
        padding: 20px;
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
