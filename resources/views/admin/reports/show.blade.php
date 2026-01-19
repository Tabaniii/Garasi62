@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-4">Detail Laporan</h1>

<div class="row g-4">
    <!-- Report Details -->
    <div class="col-lg-8">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-flag me-2"></i>Informasi Laporan
                </h5>
                <span class="badge 
                    @if($report->status == 'pending') bg-warning
                    @elseif($report->status == 'reviewed') bg-primary
                    @elseif($report->status == 'resolved') bg-success
                    @else bg-secondary
                    @endif">
                    {{ $report->status_label }}
                </span>
            </div>

            <div class="report-details">
                <div class="detail-item">
                    <label>Tanggal Laporan:</label>
                    <span>{{ $report->created_at->format('d M Y, H:i') }}</span>
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

        <!-- Car Information -->
        @if($report->car)
        <div class="info-card animate-fade-in mt-4">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-car me-2"></i>Mobil yang Dilaporkan
                </h5>
                <a href="{{ route('car.details', $report->car->id) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat Mobil
                </a>
            </div>
            <div class="car-info-grid">
                <div class="car-image">
                    @if($report->car->image && is_array($report->car->image) && count($report->car->image) > 0)
                        <img src="{{ asset('storage/' . $report->car->image[0]) }}" alt="{{ $report->car->brand }}">
                    @else
                        <div class="car-placeholder">
                            <i class="fas fa-car"></i>
                        </div>
                    @endif
                </div>
                <div class="car-details">
                    <h5>{{ strtoupper($report->car->brand) }} {{ $report->car->nama }}</h5>
                    <p><strong>Tahun:</strong> {{ $report->car->tahun }}</p>
                    <p><strong>Kilometer:</strong> {{ number_format($report->car->kilometer, 0, ',', '.') }} km</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($report->car->harga, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($report->car->status == 'approved') bg-success
                            @elseif($report->car->status == 'pending') bg-warning
                            @else bg-danger
                            @endif">
                            {{ $report->car->status == 'approved' ? 'Disetujui' : ($report->car->status == 'pending' ? 'Menunggu' : 'Ditolak') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Panel -->
    <div class="col-lg-4">
        <!-- Reporter Information -->
        <div class="info-card animate-slide-in-right mb-4">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-user me-2"></i>Informasi Pelapor
                </h5>
            </div>
            @if($report->reporter)
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details">
                    <h6>{{ $report->reporter->name }}</h6>
                    <p class="mb-1">{{ $report->reporter->email }}</p>
                    @if($report->reporter->phone)
                    <p class="mb-0"><i class="fas fa-phone me-1"></i>{{ $report->reporter->phone }}</p>
                    @endif
                </div>
            </div>
            @else
            <p class="text-muted">Informasi pelapor tidak tersedia</p>
            @endif
        </div>

        <!-- Seller Information -->
        <div class="info-card animate-slide-in-right mb-4">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-user-tie me-2"></i>Informasi Seller
                </h5>
            </div>
            @if($report->seller)
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details">
                    <h6>{{ $report->seller->name }}</h6>
                    <p class="mb-1">{{ $report->seller->email }}</p>
                    @if($report->seller->phone)
                    <p class="mb-0"><i class="fas fa-phone me-1"></i>{{ $report->seller->phone }}</p>
                    @endif
                </div>
            </div>
            @else
            <p class="text-muted">Informasi seller tidak tersedia</p>
            @endif
        </div>

        <!-- Update Status -->
        <div class="info-card animate-slide-in-right">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-tasks me-2"></i>Update Status
                </h5>
            </div>

            <form action="{{ route('admin.reports.update', $report) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>Sedang Ditinjau</option>
                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                        <option value="dismissed" {{ $report->status == 'dismissed' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="admin_notes" class="form-label">Catatan Admin</label>
                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Tambahkan catatan untuk laporan ini...">{{ $report->admin_notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-save me-2"></i>Update Status
                </button>
            </form>

            <div class="mt-3">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<style>
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

.car-info-grid {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 20px;
}

.car-image {
    width: 100%;
    height: 150px;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.car-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.car-placeholder {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.car-placeholder i {
    font-size: 48px;
}

.car-details h5 {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.car-details p {
    margin-bottom: 8px;
    color: #4b5563;
    font-size: 14px;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-avatar {
    font-size: 48px;
    color: #dc2626;
}

.user-details h6 {
    margin: 0 0 5px 0;
    font-weight: 600;
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
    .car-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
