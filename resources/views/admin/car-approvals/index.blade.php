@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-3">Persetujuan Mobil</h1>

<!-- Stats Cards -->
<div class="row g-3 mb-3">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card animate-fade-in" style="padding: 16px; animation-delay: 0.1s;">
            <div class="stat-card-icon yellow" style="width: 48px; height: 48px; font-size: 22px; margin-bottom: 12px;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-card-value animate-count-up" style="font-size: 22px;">{{ $stats['pending'] }}</div>
            <div class="stat-card-label" style="font-size: 12px;">Menunggu Persetujuan</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card animate-fade-in" style="padding: 16px; animation-delay: 0.2s;">
            <div class="stat-card-icon green" style="width: 48px; height: 48px; font-size: 22px; margin-bottom: 12px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up" style="font-size: 22px;">{{ $stats['approved'] }}</div>
            <div class="stat-card-label" style="font-size: 12px;">Disetujui</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card animate-fade-in" style="padding: 16px; animation-delay: 0.3s;">
            <div class="stat-card-icon red" style="width: 48px; height: 48px; font-size: 22px; margin-bottom: 12px;">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-card-value animate-count-up" style="font-size: 22px;">{{ $stats['rejected'] }}</div>
            <div class="stat-card-label" style="font-size: 12px;">Ditolak</div>
        </div>
    </div>
</div>

<!-- Pending Cars List -->
<div class="row g-3">
    <div class="col-12">
        <div class="info-card animate-fade-in" style="padding: 20px;">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-list me-2"></i>Mobil Menunggu Persetujuan
                </h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.car-approvals.history') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history me-1"></i>Riwayat
                    </a>
                </div>
            </div>

            @if($pendingCars->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 56px;">Gambar</th>
                                <th>Mobil</th>
                                <th>Penjual</th>
                                <th class="text-end" style="width: 140px;">Harga</th>
                                <th style="width: 130px;">Tanggal Post</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingCars as $car)
                            <tr>
                                <td>
                                    @if($car->image && is_array($car->image) && count($car->image) > 0)
                                        <img src="{{ media_url($car->image[0]) }}"
                                             alt="{{ $car->brand }}"
                                             class="car-thumbnail">
                                    @else
                                        <div class="car-thumbnail-placeholder">
                                            <i class="fas fa-car"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="car-info">
                                        <h6 class="mb-1" style="font-size: 13px;">{{ strtoupper($car->brand) }} {{ $car->nama }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>{{ $car->tahun }}
                                            <i class="fas fa-tachometer-alt ms-2 me-1"></i>{{ number_format($car->kilometer, 0, ',', '.') }} km
                                            <i class="fas fa-cogs ms-2 me-1"></i>{{ $car->transmisi }}
                                            @if($car->bahan_bakar)
                                            <i class="fas fa-gas-pump ms-2 me-1"></i>{{ $car->bahan_bakar }}
                                            @endif
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if($car->seller)
                                        <div class="seller-info">
                                            <strong>{{ $car->seller->name }}</strong><br>
                                            <small class="text-muted">{{ $car->seller->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="car-price">Rp {{ number_format($car->harga, 0, ',', '.') }}</span>
                                    @if($car->tipe == 'rent')
                                        <br><small class="text-muted">per hari</small>
                                    @endif
                                </td>
                                <td>{{ $car->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.car-approvals.show', $car) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-success approve-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#approveModal"
                                                data-car-id="{{ $car->id }}"
                                                data-car-name="{{ $car->brand }} {{ $car->nama }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-danger reject-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal"
                                                data-car-id="{{ $car->id }}"
                                                data-car-name="{{ $car->brand }} {{ $car->nama }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $pendingCars->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                        <h5>Tidak ada mobil menunggu persetujuan</h5>
                        <p>Semua mobil sudah diproses atau belum ada yang diposting.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setujui Mobil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui mobil <strong id="approveCarName"></strong>?</p>
                    <div class="mb-3">
                        <label for="approveNotes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="approveNotes" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Mobil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak mobil <strong id="rejectCarName"></strong>?</p>
                    <div class="mb-3">
                        <label for="rejectNotes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectNotes" name="notes" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.table.table-sm thead th {
    font-size: 12px;
}
.table.table-sm tbody td {
    font-size: 12px;
}
.btn-group .btn {
    padding: 4px 8px;
}
.car-thumbnail {
    width: 48px;
    height: 36px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.car-thumbnail-placeholder {
    width: 48px;
    height: 36px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.car-info h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.car-price {
    font-weight: 600;
    color: #dc2626;
    font-size: 13px;
}

.seller-info {
    font-size: 12px;
}

.info-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve button clicks
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const carId = this.dataset.carId;
            const carName = this.dataset.carName;

            document.getElementById('approveCarName').textContent = carName;
            document.getElementById('approveForm').action = `/admin/car-approvals/${carId}/approve`;
        });
    });

    // Handle reject button clicks
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const carId = this.dataset.carId;
            const carName = this.dataset.carName;

            document.getElementById('rejectCarName').textContent = carName;
            document.getElementById('rejectForm').action = `/admin/car-approvals/${carId}/reject`;
        });
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
