@extends('layouts.admin')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<h1 class="page-title mb-4">Dashboard</h1>

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
            <div class="stat-card-icon red animate-bounce-in">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['total_fund_requests'] ?? 0 }}</div>
            <div class="stat-card-label">Permintaan Dana</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.4s;">
            <div class="stat-card-icon black animate-bounce-in">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['pending_fund_requests'] ?? 0 }}</div>
            <div class="stat-card-label">Menunggu Persetujuan</div>
        </div>
    </div>
</div>

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
                <a href="{{ route('about') }}" class="btn btn-outline-dark btn-animate" style="padding: 12px 20px;">
                    <i class="fas fa-info-circle me-2"></i>Kelola Tentang
                </a>
            </div>
        </div>
    </div>
</div>

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
