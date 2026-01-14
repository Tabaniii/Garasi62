@extends('layouts.admin')

@section('header-title', 'Kelola Mobil')

@section('content')
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

@keyframes shimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Header */
.page-header-section {
    background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%) !important;
    padding: 45px 40px !important;
    border-radius: 24px !important;
    border: 1px solid #e9ecef !important;
    margin-bottom: 40px !important;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.02) !important;
    position: relative;
    overflow: hidden;
}

.page-header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #dc2626, #ef4444, #f87171, #ef4444, #dc2626);
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
}

.page-header-section::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at top right, rgba(220, 38, 38, 0.03), transparent 50%);
    pointer-events: none;
}

.page-header-content {
    display: flex !important;
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

.page-header-section .page-title {
    font-size: 36px !important;
    font-weight: 900 !important;
    background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 !important;
    letter-spacing: -0.5px;
}

.page-badge {
    padding: 8px 18px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 4px 16px rgba(220, 38, 38, 0.4), 0 0 0 0 rgba(220, 38, 38, 0.5);
    animation: pulse 2s ease-in-out infinite;
    border: 2px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.page-badge::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 3s infinite;
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
    display: inline-flex !important;
    align-items: center;
    gap: 10px;
    padding: 16px 32px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    text-decoration: none;
    border-radius: 14px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.35), 0 0 0 0 rgba(220, 38, 38, 0.5);
    border: none;
    position: relative;
    overflow: hidden;
}

.btn-add-new::before {
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

.btn-add-new:hover::before {
    width: 400px;
    height: 400px;
}

.btn-add-new:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(220, 38, 38, 0.45), 0 0 0 4px rgba(220, 38, 38, 0.1);
    background: linear-gradient(135deg, #b91c1c, #dc2626);
    color: #fff;
}

.btn-add-new i {
    position: relative;
    z-index: 1;
    transition: transform 0.3s;
}

.btn-add-new:hover i {
    transform: rotate(90deg) scale(1.1);
}

.btn-add-new i {
    font-size: 16px;
}

@media (max-width: 768px) {
    .page-header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-header-section .page-title {
        font-size: 28px !important;
    }
    
    .btn-add-new {
        width: 100%;
        justify-content: center;
    }
}

/* Grid Layout */
.cars-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
    gap: 20px !important;
    margin-bottom: 45px !important;
}

@media (max-width: 768px) {
    .cars-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
}

.car-card {
    background: #fff !important;
    border-radius: 16px !important;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.04) !important;
    border: 1px solid #e9ecef !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex !important;
    flex-direction: column;
    position: relative;
    backdrop-filter: blur(10px);
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
    transform: translateY(-10px) scale(1.01);
    box-shadow: 0 20px 60px rgba(220, 38, 38, 0.2), 0 0 0 1px rgba(220, 38, 38, 0.1) !important;
    border-color: #dc2626 !important;
}

.car-card:hover::before {
    opacity: 1;
}

/* Card Image */
.car-card-image {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
    background: linear-gradient(135deg, #f5f5f5 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
}

.car-card-image::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.03) 100%);
    pointer-events: none;
    transition: opacity 0.3s;
}

.car-card:hover .car-card-image::after {
    opacity: 0;
}

.car-main-image {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    object-position: center;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    display: block;
}

.car-card:hover .car-main-image {
    transform: scale(1.05);
}

.car-status-badge {
    display: inline-flex !important;
    align-items: center;
    gap: 3px;
    padding: 3px 10px !important;
    border-radius: 5px !important;
    font-size: 9px !important;
    font-weight: 700 !important;
    margin-top: 5px !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s;
}

.badge-rent {
    background: linear-gradient(135deg, #3b82f6, #60a5fa) !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.badge-sale {
    background: linear-gradient(135deg, #10b981, #34d399) !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.car-image-count {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.7));
    backdrop-filter: blur(12px);
    color: #fff;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s;
}

.car-card:hover .car-image-count {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
}

.car-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    padding: 15px;
}

.car-image-placeholder i {
    font-size: 48px;
    color: #9ca3af;
    opacity: 0.5;
}

/* Card Body */
.car-card-body {
    padding: 14px !important;
    flex: 1;
    display: flex !important;
    flex-direction: column;
    background: linear-gradient(to bottom, #ffffff, #fafafa);
}

.car-brand-section {
    margin-bottom: 12px !important;
    padding-bottom: 10px !important;
    border-bottom: 2px solid #f3f4f6 !important;
    position: relative;
}

.car-brand-section::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 35px;
    height: 2px;
    background: linear-gradient(90deg, #dc2626, #ef4444);
    border-radius: 2px;
}

.car-brand {
    font-size: 18px !important;
    font-weight: 900 !important;
    background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 0 6px 0 !important;
    text-transform: uppercase;
    letter-spacing: -0.5px;
    line-height: 1.2;
}

/* Car Details List */
.car-details-list {
    margin: 12px 0 !important;
    padding: 5px 0 !important;
    list-style: none !important;
    background: linear-gradient(135deg, #fafafa, #ffffff);
    border-radius: 8px;
    border: 1px solid #f3f4f6;
}

.car-detail-row {
    display: flex !important;
    align-items: center;
    gap: 10px;
    padding: 6px 8px;
    margin: 2px 0;
    border-radius: 6px;
    border-bottom: none !important;
    background: transparent;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: default;
}

.car-detail-row:hover {
    background: linear-gradient(135deg, #fafafa, #f5f5f5);
    transform: translateX(4px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.car-detail-row:last-child {
    border-bottom: none !important;
}

.detail-icon {
    width: 24px !important;
    height: 24px !important;
    display: flex !important;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #dc2626;
    font-size: 12px;
    flex-shrink: 0;
    border-radius: 5px;
    transition: all 0.3s;
}

.car-detail-row:hover .detail-icon {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.detail-text {
    display: flex !important;
    align-items: center;
    gap: 8px;
    flex: 1;
}

.detail-label {
    font-size: 11px !important;
    color: #6b7280 !important;
    font-weight: 600 !important;
    min-width: 70px;
}

.detail-value {
    font-size: 12px !important;
    color: #1a1a1a !important;
    font-weight: 700 !important;
    transition: color 0.3s;
}

.car-detail-row:hover .detail-value {
    color: #dc2626 !important;
}

.car-card-actions {
    display: flex !important;
    gap: 8px !important;
    margin-top: 14px !important;
    padding-top: 12px !important;
    border-top: 2px solid #f3f4f6 !important;
}

.btn-action {
    flex: 1 !important;
    padding: 8px 12px !important;
    border-radius: 7px !important;
    font-weight: 700 !important;
    font-size: 11px !important;
    border: 2px solid;
    cursor: pointer;
    display: flex !important;
    align-items: center;
    justify-content: center;
    gap: 5px;
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
    font-size: 12px;
    position: relative;
    z-index: 1;
    transition: transform 0.3s;
}

.btn-action:hover i {
    transform: scale(1.1);
}

.btn-action span {
    position: relative;
    z-index: 1;
}

.btn-edit-action {
    background: linear-gradient(135deg, #fff, #fafafa) !important;
    color: #f59e0b !important;
    border-color: #fbbf24 !important;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);
}

.btn-edit-action:hover {
    background: linear-gradient(135deg, #f59e0b, #fbbf24) !important;
    border-color: #f59e0b !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
}

.btn-delete-action {
    background: linear-gradient(135deg, #fff, #fafafa) !important;
    color: #dc2626 !important;
    border-color: #ef4444 !important;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.15);
}

.btn-delete-action:hover {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    border-color: #dc2626 !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
}

/* Empty State */
.empty-state {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border-radius: 20px !important;
    padding: 80px 40px !important;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06) !important;
    border: 2px dashed #e9ecef !important;
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
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #1a1a1a !important;
    margin-bottom: 12px !important;
    position: relative;
    z-index: 1;
}

.empty-state-text {
    font-size: 16px !important;
    color: #6b7280 !important;
    margin-bottom: 30px !important;
    position: relative;
    z-index: 1;
}

.btn-empty-state {
    display: inline-flex !important;
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
    color: #fff;
}

/* Back Button */
.btn-back-dashboard {
    display: inline-flex !important;
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
    color: #fff;
}

.btn-back-dashboard i {
    transition: transform 0.3s;
}

.btn-back-dashboard:hover i {
    transform: translateX(-4px);
}

/* Success Alert Enhancement */
.alert-custom {
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2) !important;
    margin-bottom: 25px !important;
}

/* Border Top Enhancement */
.border-top {
    border-top: 2px solid #e9ecef !important;
    padding-top: 30px !important;
}
</style>

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
        </div>
        <div class="car-card-body">
            <div class="car-brand-section">
                <h5 class="car-brand">{{ strtoupper($car->nama) }}</h5>
                <span class="car-status-badge {{ $car->tipe == 'rent' ? 'badge-rent' : 'badge-sale' }}">
                    {{ $car->tipe == 'rent' ? 'For Rent' : 'For Sale' }}
                </span>
            </div>
            <div class="car-details-list">
                <div class="car-detail-row">
                    <div class="detail-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="detail-text">
                        <span class="detail-label">Tahun:</span>
                        <span class="detail-value">{{ $car->tahun }}</span>
                    </div>
                </div>
                <div class="car-detail-row">
                    <div class="detail-icon">
                        <i class="fas fa-road"></i>
                    </div>
                    <div class="detail-text">
                        <span class="detail-label">Kilometer:</span>
                        <span class="detail-value">{{ number_format($car->kilometer, 0, ',', '.') }} km</span>
                    </div>
                </div>
                <div class="car-detail-row">
                    <div class="detail-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="detail-text">
                        <span class="detail-label">Transmisi:</span>
                        <span class="detail-value">{{ $car->transmisi }}</span>
                    </div>
                </div>
                <div class="car-detail-row">
                    <div class="detail-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="detail-text">
                        <span class="detail-label">Kapasitas:</span>
                        <span class="detail-value">{{ $car->kapasitasmesin }}</span>
                    </div>
                </div>
                <div class="car-detail-row">
                    <div class="detail-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="detail-text">
                        <span class="detail-label">Harga:</span>
                        <span class="detail-value">Rp {{ number_format($car->harga, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="car-detail-row">
                    <div class="detail-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="detail-text">
                        <span class="detail-label">Metode:</span>
                        <span class="detail-value">{{ $car->metode }}</span>
                    </div>
                </div>
            </div>
            <div class="car-card-actions">
                <a href="{{ route('cars.edit', $car->id) }}" class="btn-action btn-edit-action">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
                <button type="button" class="btn-action btn-delete-action w-100" 
                        data-car-id="{{ $car->id }}" 
                        data-car-name="{{ $car->nama }}" 
                        data-car-brand="{{ $car->brand }}"
                        onclick="confirmDeleteCar(this)">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
                <form id="delete-car-form-{{ $car->id }}" action="{{ route('cars.destroy', $car->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteCar(button) {
    const carId = button.getAttribute('data-car-id');
    const carName = button.getAttribute('data-car-name');
    const carBrand = button.getAttribute('data-car-brand');
    Swal.fire({
        title: '<strong style="color: #1a1a1a;">Hapus Mobil?</strong>',
        html: `<div style="text-align: left; padding: 5px 0;">
            <p style="color: #6b7280; margin-bottom: 10px; font-size: 13px;">Anda akan menghapus mobil berikut:</p>
            <div style="background: #f9fafb; padding: 12px; border-radius: 8px; border-left: 3px solid #dc2626; margin-bottom: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(135deg, #dc2626, #991b1b); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; flex-shrink: 0;">
                        <i class="fas fa-car"></i>
                    </div>
                    <div style="flex: 1;">
                        <strong style="color: #1a1a1a; display: block; margin-bottom: 3px; font-size: 14px;">${carName}</strong>
                        <span style="background: #e9ecef; padding: 2px 8px; border-radius: 4px; font-size: 11px; color: #6b7280; font-weight: 600;">
                            <i class="fas fa-tag me-1"></i>${carBrand}
                        </span>
                    </div>
                </div>
            </div>
            <div style="background: #fef2f2; padding: 10px; border-radius: 6px; border-left: 3px solid #ef4444; margin-top: 10px;">
                <p style="color: #dc2626; font-size: 12px; margin: 0; display: flex; align-items: center; gap: 6px; margin-bottom: 5px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 12px;"></i>
                    <strong>Tindakan ini tidak dapat dibatalkan!</strong>
                </p>
                <p style="color: #991b1b; font-size: 11px; margin: 0;">
                    Mobil dan semua gambar terkait akan dihapus secara permanen.
                </p>
            </div>
        </div>`,
        icon: 'warning',
        iconColor: '#dc2626',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus Mobil',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom-delete-car',
            confirmButton: 'swal2-confirm-custom-delete-car',
            cancelButton: 'swal2-cancel-custom-delete-car',
            title: 'swal2-title-custom-delete-car',
            htmlContainer: 'swal2-html-container-custom-delete-car',
            icon: 'swal2-icon-custom-delete-car'
        },
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus Mobil...',
                html: '<p style="font-size: 13px; color: #6b7280;">Mohon tunggu, mobil sedang dihapus.</p>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: {
                    popup: 'swal2-popup-custom-delete-car',
                    title: 'swal2-title-custom-delete-car'
                }
            });

            // Submit form
            const form = document.getElementById('delete-car-form-' + carId);
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '<strong style="color: #1a1a1a; font-size: 18px;">Berhasil!</strong>',
                        html: '<p style="color: #6b7280; font-size: 13px;">Mobil berhasil dihapus.</p>',
                        confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                        confirmButtonColor: '#10b981',
                        customClass: {
                            popup: 'swal2-popup-custom-delete-car',
                            confirmButton: 'swal2-confirm-custom-delete-car',
                            title: 'swal2-title-custom-delete-car'
                        },
                        buttonsStyling: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '<strong style="color: #1a1a1a; font-size: 18px;">Gagal!</strong>',
                        html: '<p style="color: #6b7280; font-size: 13px;">Terjadi kesalahan saat menghapus mobil. Silakan coba lagi.</p>',
                        confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                        confirmButtonColor: '#dc2626',
                        customClass: {
                            popup: 'swal2-popup-custom-delete-car',
                            confirmButton: 'swal2-confirm-custom-delete-car',
                            title: 'swal2-title-custom-delete-car'
                        },
                        buttonsStyling: false
                    });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: '<strong style="color: #1a1a1a;">Gagal!</strong>',
                    html: '<p style="color: #6b7280;">Terjadi kesalahan saat menghapus mobil. Silakan coba lagi.</p>',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'swal2-popup-custom-delete-car',
                        confirmButton: 'swal2-confirm-custom-delete-car',
                        title: 'swal2-title-custom-delete-car'
                    },
                    buttonsStyling: false
                });
            });
        }
    });
}
</script>

<style>
/* SweetAlert2 Custom Styling for Car Delete */
.swal2-popup-custom-delete-car {
    border-radius: 12px !important;
    padding: 20px !important;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
    border: 1px solid #e9ecef !important;
    max-width: 450px !important;
}

.swal2-title-custom-delete-car {
    font-size: 20px !important;
    font-weight: 800 !important;
    color: #1a1a1a !important;
    margin-bottom: 12px !important;
    letter-spacing: -0.3px !important;
}

.swal2-html-container-custom-delete-car {
    font-size: 13px !important;
    color: #6b7280 !important;
    line-height: 1.5 !important;
    text-align: left !important;
}

.swal2-confirm-custom-delete-car {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    color: #fff !important;
    padding: 10px 20px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 13px !important;
    border: none !important;
    transition: all 0.3s !important;
    box-shadow: 0 3px 10px rgba(220, 38, 38, 0.3) !important;
}

.swal2-confirm-custom-delete-car:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
    background: linear-gradient(135deg, #b91c1c, #dc2626) !important;
}

.swal2-cancel-custom-delete-car {
    background: linear-gradient(135deg, #fff, #fafafa) !important;
    color: #6b7280 !important;
    padding: 10px 20px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 13px !important;
    border: 2px solid #e9ecef !important;
    transition: all 0.3s !important;
}

.swal2-cancel-custom-delete-car:hover {
    background: linear-gradient(135deg, #f9fafb, #f3f4f6) !important;
    border-color: #d1d5db !important;
    transform: translateY(-2px) !important;
    color: #4b5563 !important;
}

.swal2-icon-custom-delete-car.swal2-warning {
    border-color: #dc2626 !important;
    color: #dc2626 !important;
    border-width: 3px !important;
    width: 50px !important;
    height: 50px !important;
    margin-bottom: 15px !important;
}

.swal2-icon-custom-delete-car.swal2-success {
    border-color: #10b981 !important;
    color: #10b981 !important;
    width: 50px !important;
    height: 50px !important;
}
</style>
@endpush
@endsection


