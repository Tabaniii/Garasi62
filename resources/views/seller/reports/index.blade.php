@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-4">Laporan Mobil Saya</h1>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-lg-6 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
            <div class="stat-card-icon yellow animate-bounce-in">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['pending'] }}</div>
            <div class="stat-card-label">Laporan Menunggu Review</div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
            <div class="stat-card-icon red animate-bounce-in">
                <i class="fas fa-flag"></i>
            </div>
            <div class="stat-card-value animate-count-up">{{ $stats['total'] }}</div>
            <div class="stat-card-label">Total Laporan</div>
        </div>
    </div>
</div>

<!-- Reports Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-flag me-2"></i>Daftar Laporan Mobil Saya
                </h5>
            </div>

            @if($reports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Mobil</th>
                                <th>Pelapor</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Detail</th>
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
                                    <a href="{{ route('seller.reports.show', $report) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail Laporan">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Detail Report -->
                            <div class="modal fade" id="reportModal{{ $report->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Laporan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="report-details">
                                                <div class="detail-item">
                                                    <label>Tanggal Laporan:</label>
                                                    <span>{{ $report->created_at->format('d M Y, H:i') }}</span>
                                                </div>

                                                <div class="detail-item">
                                                    <label>Mobil:</label>
                                                    <span><strong>{{ strtoupper($report->car->brand ?? 'N/A') }} {{ $report->car->nama ?? '' }}</strong></span>
                                                </div>

                                                <div class="detail-item">
                                                    <label>Pelapor:</label>
                                                    <span>{{ $report->reporter->name ?? 'N/A' }} ({{ $report->reporter->email ?? 'N/A' }})</span>
                                                </div>

                                                <div class="detail-item">
                                                    <label>Alasan:</label>
                                                    <span class="badge bg-info">{{ $report->reason_label }}</span>
                                                </div>

                                                <div class="detail-item">
                                                    <label>Detail Laporan:</label>
                                                    <div class="report-message">
                                                        {{ $report->message }}
                                                    </div>
                                                </div>

                                                <div class="detail-item">
                                                    <label>Status:</label>
                                                    <span class="badge 
                                                        @if($report->status == 'pending') bg-warning
                                                        @elseif($report->status == 'reviewed') bg-primary
                                                        @elseif($report->status == 'resolved') bg-success
                                                        @else bg-secondary
                                                        @endif">
                                                        {{ $report->status_label }}
                                                    </span>
                                                </div>

                                                @if($report->admin_notes)
                                                <div class="detail-item">
                                                    <label>Catatan Admin:</label>
                                                    <div class="admin-notes">
                                                        {{ $report->admin_notes }}
                                                    </div>
                                                </div>
                                                @endif

                                                @if($report->reviewer)
                                                <div class="detail-item">
                                                    <label>Ditinjau Oleh:</label>
                                                    <span>{{ $report->reviewer->name }}</span>
                                                    <small class="text-muted">({{ $report->reviewed_at->format('d M Y, H:i') }})</small>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                        <p>Laporan dari user akan muncul di sini jika ada yang melaporkan mobil Anda.</p>
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

.report-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item span {
    color: #1a1a1a;
    font-size: 15px;
}

.report-message {
    background: #f8f9fa;
    padding: 16px;
    border-radius: 5px;
    border-left: 4px solid #dc2626;
    color: #4b5563;
    line-height: 1.6;
    white-space: pre-wrap;
}

.admin-notes {
    background: #fef3c7;
    padding: 16px;
    border-radius: 5px;
    border-left: 4px solid #f59e0b;
    color: #4b5563;
    line-height: 1.6;
    white-space: pre-wrap;
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
