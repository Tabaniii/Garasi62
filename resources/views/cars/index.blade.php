@extends('layouts.admin')

@section('header-title', 'Kelola Mobil')

@section('content')
        @if(session('success'))
<div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

<div class="page-header-section mb-5">
    <div class="page-header-content">
        <div class="page-header-text">
            <div class="page-title-wrapper">
                <h1 class="page-title">Kelola Mobil Saya</h1>
                @if($cars->count() > 0)
                <span class="page-badge">{{ $cars->count() }} Mobil</span>
                @endif
            </div>
            <p class="page-subtitle">
                <i class="fas fa-info-circle me-2"></i>Daftar mobil yang telah Anda tambahkan
            </p>
                    </div>
        <a href="{{ route('cars.create') }}" class="btn-add-new">
            <i class="fas fa-plus"></i>
            <span>Tambah Mobil Baru</span>
                    </a>
            </div>
        </div>

@if($cars->count() > 0)
<div class="cars-grid">
    @foreach($cars as $index => $car)
    <div class="car-card animate-fade-in" data-animation-delay="{{ $index * 0.1 }}">
        <div class="car-card-image">
            @if($car->image && is_array($car->image) && count($car->image) > 0)
            <img src="{{ asset('storage/' . $car->image[0]) }}" alt="{{ $car->brand }}" class="car-main-image">
            <div class="car-image-count">
                <i class="fas fa-images"></i>
                <span>{{ count($car->image) }}</span>
            </div>
            @else
            <div class="car-image-placeholder">
                <i class="fas fa-car"></i>
            </div>
            @endif
            <div class="car-status-badge-top {{ $car->tipe == 'rent' ? 'badge-rent-top' : 'badge-sale-top' }}">
                {{ $car->tipe == 'rent' ? 'Sewa' : 'Jual' }}
            </div>
        </div>
        <div class="car-card-body">
            <div class="car-brand-section">
                <h5 class="car-brand">{{ strtoupper($car->brand) }}</h5>
                <span class="car-year-badge">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $car->tahun }}
                </span>
            </div>
            <div class="car-details-grid">
                <div class="car-detail-item">
                    <div class="detail-icon-wrapper">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Kilometer</span>
                        <strong class="detail-value">{{ $car->kilometer }} km</strong>
                    </div>
                </div>
                <div class="car-detail-item">
                    <div class="detail-icon-wrapper">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Transmisi</span>
                        <strong class="detail-value">{{ $car->transmisi }}</strong>
                    </div>
                </div>
                <div class="car-detail-item">
                    <div class="detail-icon-wrapper">
                        <i class="fas fa-engine"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Kapasitas</span>
                        <strong class="detail-value">{{ $car->kapasitasmesin }}</strong>
                    </div>
                </div>
                <div class="car-detail-item">
                    <div class="detail-icon-wrapper">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Metode</span>
                        <strong class="detail-value">{{ $car->metode }}</strong>
                    </div>
                </div>
            </div>
            <div class="car-price-section">
                <div class="price-label">Harga</div>
                <div class="price-value">Rp {{ number_format($car->harga, 0, ',', '.') }}</div>
            </div>
            <div class="car-card-actions">
                <a href="{{ route('cars.edit', $car->id) }}" class="btn-action btn-edit-action">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
                <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="d-inline flex-fill" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mobil ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete-action w-100">
                        <i class="fas fa-trash"></i>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
        @else
<div class="empty-state animate-fade-in">
    <div class="empty-state-icon">
        <i class="fas fa-car"></i>
    </div>
    <h4 class="empty-state-title">Belum ada mobil</h4>
    <p class="empty-state-text">Mulai dengan menambahkan mobil pertama Anda</p>
    <a href="{{ route('cars.create') }}" class="btn-empty-state">
        <i class="fas fa-plus"></i>
        <span>Tambah Mobil Baru</span>
    </a>
        </div>
        @endif

<div class="mt-5 pt-4 border-top">
    <a href="{{ route('dashboard') }}" class="btn-back-dashboard">
        <i class="fas fa-arrow-left me-2"></i>
        <span>Kembali ke Dashboard</span>
                </a>
            </div>

@push('styles')
<style>
/* Global Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Header */
.page-header-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 40px 35px;
    border-radius: 20px;
    border: 1px solid #e9ecef;
    margin-bottom: 35px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    position: relative;
    overflow: hidden;
}

.page-header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #dc2626, #ef4444, #dc2626);
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 25px;
    position: relative;
    z-index: 1;
}

.page-title-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.page-title {
    font-size: 36px;
    font-weight: 900;
    background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
    letter-spacing: -0.5px;
}

.page-badge {
    padding: 8px 16px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.page-subtitle {
    font-size: 15px;
    color: #6b7280;
    margin: 0;
    font-weight: 500;
}

.page-subtitle i {
    color: #dc2626;
    margin-right: 8px;
}

.btn-add-new {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    border: none;
}

.btn-add-new:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
    background: linear-gradient(135deg, #b91c1c, #dc2626);
}

.btn-add-new i {
    font-size: 16px;
}

@media (max-width: 768px) {
    .page-header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-title {
        font-size: 28px;
    }
    
    .btn-add-new {
        width: 100%;
        justify-content: center;
    }
}

/* Grid Layout */
.cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 28px;
    margin-bottom: 45px;
}

@media (max-width: 768px) {
    .cars-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

.car-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    position: relative;
}

.car-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 20px;
    padding: 2px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.4s;
}

.car-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 40px rgba(220, 38, 38, 0.15);
    border-color: #dc2626;
}

.car-card:hover::before {
    opacity: 1;
}

/* Card Image */
.car-card-image {
    position: relative;
    width: 100%;
    height: 260px;
    overflow: hidden;
    background: linear-gradient(135deg, #f5f5f5 0%, #e9ecef 100%);
}

.car-main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.car-card:hover .car-main-image {
    transform: scale(1.1);
}

.car-status-badge-top {
    position: absolute;
    top: 16px;
    right: 16px;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
}

.badge-rent-top {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: #fff;
}

.badge-sale-top {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: #fff;
}

.car-image-count {
    position: absolute;
    bottom: 16px;
    left: 16px;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(10px);
    color: #fff;
    padding: 8px 14px;
    border-radius: 25px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.car-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
}

.car-image-placeholder i {
    font-size: 72px;
    color: #9ca3af;
    opacity: 0.5;
}

/* Card Body */
.car-card-body {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
}

.car-brand-section {
    margin-bottom: 18px;
}

.car-brand {
    font-size: 24px;
    font-weight: 900;
    color: #1a1a1a;
    margin: 0 0 10px 0;
    text-transform: uppercase;
    letter-spacing: -0.5px;
    line-height: 1.2;
}

.car-year-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    color: #4b5563;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid #e5e7eb;
}

.car-year-badge i {
    font-size: 12px;
    color: #dc2626;
}

.car-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin: 18px 0;
}

@media (max-width: 480px) {
    .car-details-grid {
        grid-template-columns: 1fr;
    }
}

.car-detail-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: linear-gradient(135deg, #f9fafb, #f3f4f6);
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s;
}

.car-detail-item:hover {
    background: linear-gradient(135deg, #f3f4f6, #e9ecef);
    transform: translateX(4px);
}

.detail-icon-wrapper {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    border-radius: 8px;
    font-size: 15px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
}

.detail-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
    flex: 1;
    min-width: 0;
}

.detail-label {
    font-size: 10px;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 14px;
    font-weight: 700;
    color: #1a1a1a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.car-price-section {
    background: linear-gradient(135deg, #dc2626, #ef4444, #dc2626);
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
    color: #fff;
    padding: 20px;
    border-radius: 14px;
    margin-top: 18px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    position: relative;
    overflow: hidden;
}

.car-price-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 10s linear infinite;
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.price-label {
    font-size: 11px;
    text-transform: uppercase;
    opacity: 0.95;
    margin-bottom: 6px;
    font-weight: 700;
    letter-spacing: 1px;
    position: relative;
    z-index: 1;
}

.price-value {
    font-size: 26px;
    font-weight: 900;
    letter-spacing: -0.5px;
    position: relative;
    z-index: 1;
}

.car-card-actions {
    display: flex;
    gap: 12px;
    margin-top: 22px;
    padding-top: 22px;
    border-top: 2px solid #f3f4f6;
}

.btn-action {
    flex: 1;
    padding: 14px 16px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.btn-action::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-action:hover::before {
    width: 300px;
    height: 300px;
}

.btn-action i {
    position: relative;
    z-index: 1;
}

.btn-action span {
    position: relative;
    z-index: 1;
}

.btn-edit-action {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    color: #fff;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-edit-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    background: linear-gradient(135deg, #d97706, #f59e0b);
}

.btn-delete-action {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.btn-delete-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    background: linear-gradient(135deg, #b91c1c, #dc2626);
}

/* Empty State */
.empty-state {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 20px;
    padding: 80px 40px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    border: 2px dashed #e9ecef;
    position: relative;
    overflow: hidden;
}

.empty-state::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(220, 38, 38, 0.05) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 25px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    position: relative;
    z-index: 1;
}

.empty-state-icon i {
    font-size: 56px;
    color: #9ca3af;
}

.empty-state-title {
    font-size: 28px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 12px;
    position: relative;
    z-index: 1;
}

.empty-state-text {
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 30px;
    position: relative;
    z-index: 1;
}

.btn-empty-state {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 32px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    position: relative;
    z-index: 1;
}

.btn-empty-state:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
    background: linear-gradient(135deg, #b91c1c, #dc2626);
}

/* Back Button */
.btn-back-dashboard {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #1a1a1a, #374151);
    color: #fff;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    border: none;
}

.btn-back-dashboard:hover {
    background: linear-gradient(135deg, #000000, #1a1a1a);
    transform: translateX(-6px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.btn-back-dashboard i {
    transition: transform 0.3s;
}

.btn-back-dashboard:hover i {
    transform: translateX(-4px);
}

/* Success Alert Enhancement */
.alert-custom {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2);
    margin-bottom: 25px;
}

/* Border Top Enhancement */
.border-top {
    border-top: 2px solid #e9ecef !important;
    padding-top: 30px !important;
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carCards = document.querySelectorAll('.car-card[data-animation-delay]');
        carCards.forEach(function(card) {
            const delay = card.getAttribute('data-animation-delay');
            card.style.animationDelay = delay + 's';
            card.style.opacity = '0';
        });
        
        // Trigger animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                }
            });
        }, observerOptions);
        
        carCards.forEach(function(card) {
            observer.observe(card);
        });
    });
</script>
@endpush
@endsection

