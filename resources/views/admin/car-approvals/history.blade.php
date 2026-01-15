@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-4">Riwayat Persetujuan Mobil</h1>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
            <div class="stat-card-icon green animate-bounce-in">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $approvals->where('action', 'approved')->count() }}</div>
            <div class="stat-card-label">Total Disetujui</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
            <div class="stat-card-icon red animate-bounce-in">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $approvals->where('action', 'rejected')->count() }}</div>
            <div class="stat-card-label">Total Ditolak</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.3s;">
            <div class="stat-card-icon blue animate-bounce-in">
                <i class="fas fa-history"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $approvals->total() }}</div>
            <div class="stat-card-label">Total Aksi</div>
        </div>
    </div>
</div>

<!-- History Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-history me-2"></i>Riwayat Persetujuan
                </h5>
                <a href="{{ route('admin.car-approvals.index') }}" class="btn btn-sm btn-danger">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

            @if($approvals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Mobil</th>
                                <th>Penjual</th>
                                <th>Admin</th>
                                <th>Aksi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvals as $approval)
                            <tr>
                                <td>
                                    <div class="date-info">
                                        <strong>{{ $approval->approved_at->format('d M Y') }}</strong><br>
                                        <small class="text-muted">{{ $approval->approved_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($approval->car)
                                        <div class="car-info">
                                            <h6 class="mb-1">{{ strtoupper($approval->car->brand) }} {{ $approval->car->nama }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>{{ $approval->car->tahun }}
                                                <i class="fas fa-tachometer-alt ms-2 me-1"></i>{{ number_format($approval->car->kilometer, 0, ',', '.') }} km
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">Mobil tidak ditemukan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($approval->car && $approval->car->seller)
                                        <div class="seller-info">
                                            <strong>{{ $approval->car->seller->name }}</strong><br>
                                            <small class="text-muted">{{ $approval->car->seller->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($approval->admin)
                                        <div class="admin-info">
                                            <strong>{{ $approval->admin->name }}</strong><br>
                                            <small class="text-muted">{{ $approval->admin->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $approval->action == 'approved' ? 'bg-success' : 'bg-danger' }} action-badge">
                                        @if($approval->action == 'approved')
                                            <i class="fas fa-check-circle me-1"></i>Disetujui
                                        @else
                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if($approval->notes)
                                        <div class="notes-preview" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $approval->notes }}">
                                            {{ Str::limit($approval->notes, 50) }}
                                            @if(strlen($approval->notes) > 50)
                                                <span class="text-primary">...</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $approvals->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-history fa-3x mb-3 text-secondary"></i>
                        <h5>Belum ada riwayat persetujuan</h5>
                        <p>Riwayat persetujuan mobil akan muncul di sini setelah admin memproses mobil yang diposting seller.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.date-info {
    font-size: 14px;
}

.car-info h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.action-badge {
    font-size: 12px;
    padding: 6px 12px;
}

.notes-preview {
    max-width: 200px;
    cursor: help;
    font-size: 13px;
    line-height: 1.4;
}

.seller-info, .admin-info {
    font-size: 13px;
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
    .table-responsive {
        font-size: 12px;
    }

    .car-info h6 {
        font-size: 12px;
    }

    .seller-info, .admin-info {
        font-size: 11px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Animation for count up
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
