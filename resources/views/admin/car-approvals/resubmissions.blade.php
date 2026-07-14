@extends('layouts.admin')

@section('content')
<h1 class="page-title">Pengajuan Ulang Mobil</h1>

<div class="info-card mb-4">
    <div class="info-card-header">
        <h5 class="info-card-title">Daftar Mobil Diajukan Ulang</h5>
        <a href="{{ route('admin.car-approvals.index') }}" class="info-card-link">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Persetujuan
        </a>
    </div>
    <p class="mb-0 text-muted">
        Menampilkan mobil berstatus pending yang sebelumnya ditolak oleh admin. Gunakan halaman ini untuk memprioritaskan peninjauan ulang.
    </p>
</div>

@if($resubmittedCars->count() > 0)
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Gambar</th>
                <th>Mobil</th>
                <th>Seller</th>
                <th>Ditolak Pada</th>
                <th>Catatan Seller</th>
                <th>Alasan Penolakan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resubmittedCars as $car)
                @php
                    $lastRejection = $car->approvals->sortByDesc(function($a){ return $a->approved_at ?? $a->created_at; })->first();
                @endphp
                <tr>
                    <td>
                        @if(is_array($car->image) && count($car->image) > 0)
                            <img src="{{ media_url($car->image[0]) }}" alt="{{ $car->brand }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                        @else
                            <div class="car-thumbnail-placeholder">
                                <i class="fas fa-car"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <strong>{{ strtoupper($car->brand) }} {{ $car->nama }}</strong>
                            <span class="badge bg-warning"><i class="fas fa-undo me-1"></i>Pengajuan Ulang</span>
                            <div class="text-muted">
                                <i class="fas fa-calendar-alt ms-2 me-1"></i>{{ $car->tahun }}
                                <i class="fas fa-tachometer-alt ms-2 me-1"></i>{{ number_format($car->kilometer, 0, ',', '.') }} km
                            </div>
                        </div>
                    </td>
                    <td>
                        {{ $car->seller?->name ?? '—' }}
                    </td>
                    <td>
                        {{ ($lastRejection->approved_at ?? $lastRejection->created_at ?? $car->updated_at)->format('d M Y, H:i') }}
                    </td>
                    <td style="min-width: 260px;">
                        @if(!empty($car->resubmission_notes))
                            <div class="alert alert-warning mb-0 p-2" style="font-size: 0.9rem;">
                                <i class="fas fa-sticky-note me-1"></i>
                                {{ $car->resubmission_notes }}
                                @if(!empty($car->resubmitted_at))
                                    <br><small class="text-muted"><i class="fas fa-clock me-1"></i>Dikirim: {{ \Carbon\Carbon::parse($car->resubmitted_at)->format('d M Y, H:i') }}</small>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">Tidak ada catatan</span>
                        @endif
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
                        <a href="{{ route('admin.car-approvals.show', $car->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>Tinjau
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $resubmittedCars->links() }}
    </div>
@else
<div class="alert alert-success">
    <i class="fas fa-check-circle me-1"></i>
    Tidak ada mobil yang diajukan ulang saat ini.
</div>
@endif
@endsection
