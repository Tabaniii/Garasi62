@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-4">Laporan Mobil</h1>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
            <div class="stat-card-icon yellow animate-bounce-in">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['pending'] }}</div>
            <div class="stat-card-label">Menunggu Review</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
            <div class="stat-card-icon blue animate-bounce-in">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['reviewed'] }}</div>
            <div class="stat-card-label">Sedang Ditinjau</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.3s;">
            <div class="stat-card-icon green animate-bounce-in">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['resolved'] }}</div>
            <div class="stat-card-label">Selesai</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.4s;">
            <div class="stat-card-icon red animate-bounce-in">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['dismissed'] }}</div>
            <div class="stat-card-label">Ditolak</div>
        </div>
    </div>
</div>

<!-- Reports Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-flag me-2"></i>Daftar Laporan
                </h5>
                <div class="btn-group">
                    <a href="{{ route('admin.reports.index') }}?status=pending" class="btn btn-sm btn-outline-warning">Pending</a>
                    <a href="{{ route('admin.reports.index') }}?status=reviewed" class="btn btn-sm btn-outline-primary">Reviewed</a>
                    <a href="{{ route('admin.reports.index') }}?status=resolved" class="btn btn-sm btn-outline-success">Resolved</a>
                </div>
            </div>

            @if($reports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Mobil</th>
                                <th>Pelapor</th>
                                <th>Seller</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td>
                                    <div class="date-info">
                                        <strong>{{ $report->created_at->format('d M Y') }}</strong><br>
                                        <small class="text-muted">{{ $report->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($report->car)
                                        <div class="car-info">
                                            <h6 class="mb-1">{{ strtoupper($report->car->brand) }} {{ $report->car->nama }}</h6>
                                            <small class="text-muted">
                                                <a href="{{ route('car.details', $report->car->id) }}" target="_blank">
                                                    <i class="fas fa-external-link-alt"></i> Lihat Mobil
                                                </a>
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">Mobil tidak ditemukan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->reporter)
                                        <div class="user-info">
                                            <strong>{{ $report->reporter->name }}</strong><br>
                                            <small class="text-muted">{{ $report->reporter->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->seller)
                                        <div class="user-info">
                                            <strong>{{ $report->seller->name }}</strong><br>
                                            <small class="text-muted">{{ $report->seller->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $report->reason_label }}</span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($report->status == 'pending') bg-warning
                                        @elseif($report->status == 'reviewed') bg-primary
                                        @elseif($report->status == 'resolved') bg-success
                                        @else bg-secondary
                                        @endif">
                                        {{ $report->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reports->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-flag fa-3x mb-3" style="opacity: 0.3;"></i>
                        <h5>Belum ada laporan</h5>
                        <p>Laporan dari user akan muncul di sini.</p>
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

.user-info {
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
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
