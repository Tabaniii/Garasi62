@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-4">Dashboard Admin</h1>

<!-- Statistik Cards -->
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
            <div class="stat-card-icon red animate-bounce-in">
                <i class="fas fa-car"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['total_cars'] ?? 0 }}</div>
            <div class="stat-card-label">Total Mobil</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
            <div class="stat-card-icon black animate-bounce-in">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="stat-card-label">Total Pengguna</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.3s;">
            <div class="stat-card-icon yellow animate-bounce-in">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['pending_car_approvals'] ?? 0 }}</div>
            <div class="stat-card-label">Mobil Menunggu Approval</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.4s;">
            <div class="stat-card-icon green animate-bounce-in">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['approved_cars'] ?? 0 }}</div>
            <div class="stat-card-label">Mobil Disetujui</div>
        </div>
    </div>
</div>

<!-- Car Approval Section -->
@if($stats['pending_car_approvals'] > 0)
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-tasks me-2"></i>Mobil Menunggu Persetujuan
                </h5>
                <a href="{{ route('admin.car-approvals.index') }}" class="btn btn-sm btn-danger">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                    <p>Ada {{ $stats['pending_car_approvals'] }} mobil yang menunggu persetujuan Anda</p>
                    <a href="{{ route('admin.car-approvals.index') }}" class="btn btn-danger mt-3">
                        <i class="fas fa-check-circle me-2"></i>Kelola Persetujuan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Chart dan Info Cards Row -->
<div class="row g-4 mb-5">
    <!-- Chart Card -->
    <div class="col-lg-8">
        <div class="chart-card animate-slide-in-left">
            <h3 class="chart-card-title">Statistik Mobil</h3>
            <canvas id="carChart" height="100"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="info-card animate-slide-in-right">
            <div class="info-card-header">
                <h5 class="info-card-title">Aksi Cepat</h5>
            </div>
            <div class="d-grid gap-3">
                <a href="{{ route('index') }}" class="btn btn-danger btn-animate" style="background: linear-gradient(135deg, #dc2626, #991b1b); border: none; padding: 14px 20px;">
                    <i class="fas fa-home me-2"></i>Kunjungi Halaman Utama
                </a>
                <a href="{{ route('cars.create') }}" class="btn btn-outline-danger btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-plus me-2"></i>Tambah Mobil Baru
                </a>
                <a href="{{ route('cars.index') }}" class="btn btn-outline-dark btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-list me-2"></i>Lihat Semua Mobil
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-outline-dark btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-users me-2"></i>Kelola Pengguna
                </a>
                <a href="{{ route('blogs.admin.index') }}" class="btn btn-outline-danger btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-blog me-2"></i>Kelola Blog
                </a>
                <a href="{{ route('admin.car-approvals.index') }}" class="btn btn-outline-warning btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-tasks me-2"></i>Persetujuan Mobil
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Mobil Terbaru Section -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-car me-2"></i>Mobil Terbaru
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
                        @if($car->seller)
                        <p class="seller-info">
                            <i class="fas fa-user"></i> {{ $car->seller->name }}
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
                    <p>Belum ada mobil yang ditambahkan</p>
                    <a href="{{ route('cars.create') }}" class="btn btn-danger mt-3">
                        <i class="fas fa-plus me-2"></i>Tambah Mobil Pertama
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-quote-right me-2"></i>Testimoni Terbaru
                </h5>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-danger" style="font-size: 12px; padding: 6px 12px;">
                        <i class="fas fa-check-circle me-1"></i>{{ $stats['active_testimonials'] ?? 0 }} Aktif
                    </span>
                    <a href="{{ route('testimonials.admin.index') }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-eye me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            @if(isset($stats['recent_testimonials']) && $stats['recent_testimonials']->count() > 0)
            <div class="testimonials-grid">
                @foreach($stats['recent_testimonials'] as $testimonial)
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            @if($testimonial->image)
                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(mb_substr($testimonial->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="testimonial-info">
                            <h6 class="testimonial-name">{{ $testimonial->name }}</h6>
                            @if($testimonial->position || $testimonial->company)
                                <p class="testimonial-role">
                                    {{ $testimonial->position }}
                                    @if($testimonial->position && $testimonial->company)
                                        ,
                                    @endif
                                    {{ $testimonial->company }}
                                </p>
                            @endif
                        </div>
                        <div class="testimonial-rating">
                            @for($i = 0; $i < $testimonial->rating; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                            @for($i = $testimonial->rating; $i < 5; $i++)
                                <i class="far fa-star text-muted"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="testimonial-body">
                        <p class="testimonial-message">
                            <i class="fas fa-quote-left text-danger me-2" style="opacity: 0.3;"></i>
                            {{ Str::limit($testimonial->message, 120) }}
                            @if(strlen($testimonial->message) > 120)
                                <span class="text-muted">...</span>
                            @endif
                        </p>
                    </div>
                    <div class="testimonial-footer">
                        <small class="text-muted">
                            <i class="far fa-calendar me-1"></i>
                            {{ $testimonial->created_at->format('d M Y') }}
                        </small>
                        <span class="badge {{ $testimonial->is_active ? 'bg-success' : 'bg-secondary' }}" style="font-size: 10px;">
                            {{ $testimonial->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-quote-right fa-3x mb-3" style="opacity: 0.3;"></i>
                    <p>Belum ada testimoni yang ditambahkan</p>
                    <a href="{{ route('testimonials.admin.create') }}" class="btn btn-danger mt-3">
                        <i class="fas fa-plus me-2"></i>Tambah Testimoni Pertama
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
    right: 8px;
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
    margin: 0 0 8px 0;
}

.seller-info {
    font-size: 11px;
    color: #6b7280;
    margin: 0;
}

.seller-info i {
    margin-right: 4px;
    color: #dc2626;
}

.info-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

@media (max-width: 768px) {
    .recent-cars-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
}

/* Testimonials Grid Styles */
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

.testimonial-card {
    background: #fff;
    border-radius: 5px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.testimonial-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #dc2626, #991b1b);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s;
}

.testimonial-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(220, 38, 38, 0.15);
    border-color: #dc2626;
}

.testimonial-card:hover::before {
    transform: scaleX(1);
}

.testimonial-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
    position: relative;
}

.testimonial-avatar {
    flex-shrink: 0;
    position: relative;
}

.testimonial-avatar img {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #dc2626;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    transition: transform 0.3s;
}

.testimonial-card:hover .testimonial-avatar img {
    transform: scale(1.1);
}

.avatar-placeholder {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 20px;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    transition: transform 0.3s;
}

.testimonial-card:hover .avatar-placeholder {
    transform: scale(1.1);
}

.testimonial-info {
    flex: 1;
    min-width: 0;
}

.testimonial-name {
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 4px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.testimonial-role {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.testimonial-rating {
    flex-shrink: 0;
    display: flex;
    gap: 2px;
    font-size: 12px;
}

.testimonial-body {
    margin-bottom: 16px;
    padding-left: 8px;
}

.testimonial-message {
    font-size: 14px;
    line-height: 1.7;
    color: #4b5563;
    margin: 0;
    font-style: italic;
}

.testimonial-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.testimonial-footer small {
    font-size: 12px;
}

@media (max-width: 768px) {
    .testimonials-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .testimonial-card {
        padding: 20px;
    }
}
</style>

@push('scripts')
<script>
    // Chart untuk Statistik Mobil
    const ctx = document.getElementById('carChart').getContext('2d');

    // Data untuk chart (contoh data)
    const carData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
            label: 'Mobil Sewa',
            data: [12, 19, 15, 25, 22, 30],
            backgroundColor: 'rgba(220, 38, 38, 0.2)',
            borderColor: '#dc2626',
            borderWidth: 2,
            tension: 0.4
        }, {
            label: 'Mobil Beli',
            data: [8, 15, 12, 20, 18, 25],
            backgroundColor: 'rgba(0, 0, 0, 0.2)',
            borderColor: '#000000',
            borderWidth: 2,
            tension: 0.4
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: carData,
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
