@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-4">Pengajuan Ulang Mobil</h1>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Berikut adalah mobil yang ditolak oleh admin. Anda dapat mengedit data mobil dan mengajukan ulang dengan menyertakan catatan ke admin.
        </div>
    </div>
</div>

@if($cars->count() > 0)
<div class="row g-4">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Gambar</th>
                        <th>Mobil</th>
                        <th>Alasan Penolakan</th>
                        <th>Tanggal Ditolak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cars as $car)
                        @php
                            $lastRejection = $car->approvals->where('action', 'rejected')->sortByDesc(function($a){ return $a->approved_at ?? $a->created_at; })->first();
                            $lastResubmission = $car->approvals->where('action', 'resubmitted')->sortByDesc(function($a){ return $a->approved_at ?? $a->created_at; })->first();
                        @endphp
                        <tr>
                            <td>
                                @if(is_array($car->image) && count($car->image) > 0)
                                    <img src="{{ asset('storage/' . $car->image[0]) }}" alt="{{ $car->brand }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                @else
                                    <div class="car-thumbnail-placeholder">
                                        <i class="fas fa-car"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <strong>{{ strtoupper($car->brand) }} {{ $car->nama }}</strong><br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>{{ $car->tahun }}
                                            <i class="fas fa-tachometer-alt ms-2 me-1"></i>{{ number_format($car->kilometer, 0, ',', '.') }} km
                                        </small>
                                    </div>
                                    <span class="badge bg-danger ms-2">Ditolak</span>
                                    @if($lastResubmission)
                                        <span class="badge bg-warning ms-2">Pengajuan Ulang</span>
                                    @endif
                                </div>
                            </td>
                            <td style="min-width: 260px;">
                                @if($lastRejection && $lastRejection->notes)
                                    <div class="alert alert-danger mb-0 p-2" style="font-size: 0.9rem;">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $lastRejection->notes }}
                                    </div>
                                @else
                                    <span class="text-muted">Tidak ada catatan</span>
                                @endif
                            </td>
                            <td>
                                {{ ($lastRejection->approved_at ?? $lastRejection->created_at ?? $car->updated_at)->format('d M Y, H:i') }}
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#resubmitModal{{ $car->id }}">
                                        <i class="fas fa-undo me-1"></i>Ajukan Ulang
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="resubmitModal{{ $car->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajukan Ulang Mobil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('seller.resubmissions.resubmit', $car->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="mb-2">Anda akan mengajukan ulang: <strong>{{ strtoupper($car->brand) }} {{ $car->nama }}</strong></p>
                                            <div class="mb-3">
                                                <label for="notes{{ $car->id }}" class="form-label">Catatan ke Admin (Opsional)</label>
                                                <textarea name="notes" id="notes{{ $car->id }}" class="form-control" rows="3" placeholder="Sampaikan perbaikan/penjelasan untuk pengajuan ulang..."></textarea>
                                            </div>
                                            @if($lastRejection && $lastRejection->notes)
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-sticky-note me-1"></i>
                                                    Catatan penolakan sebelumnya: {{ $lastRejection->notes }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-paper-plane me-1"></i>Kirim Pengajuan Ulang
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $cars->links() }}
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <div class="text-muted">
        <i class="fas fa-times-circle fa-3x mb-3 text-danger"></i>
        <h5>Tidak ada mobil yang ditolak</h5>
        <p>Semua mobil Anda telah disetujui atau belum ada pengajuan sebelumnya.</p>
    </div>
    <a href="{{ route('cars.create') }}" class="btn btn-danger mt-3">
        <i class="fas fa-plus me-2"></i>Tambah Mobil Baru
    </a>
</div>
@endif
@endsection
