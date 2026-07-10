@extends('layouts.admin')

@section('content')
    <h1 class="page-title mb-4">Pengajuan Ulang Mobil</h1>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Info:</strong> Silakan perbaiki mobil Anda sesuai catatan penolakan, lalu klik tombol <strong>Ajukan
                    Ulang</strong> untuk pemeriksaan kembali.
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cars as $car)
                                @php
                                    $lastRejection = $car->approvals->where('action', 'rejected')->sortByDesc(function ($a) {
                                        return $a->approved_at ?? $a->created_at;
                                    })->first();
                                @endphp
                                <tr>
                                    <td>
                                        @if(is_array($car->image) && count($car->image) > 0)
                                            <img src="{{ asset('storage/' . $car->image[0]) }}" alt="{{ $car->brand }}"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
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
                                                    <i
                                                        class="fas fa-tachometer-alt ms-2 me-1"></i>{{ number_format($car->kilometer, 0, ',', '.') }}
                                                    km
                                                </small>
                                            </div>
                                            <span class="badge bg-danger ms-2">Ditolak</span>
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
                                        <div class="d-flex flex-column gap-2">
                                            <form id="resubmit-form-{{ $car->id }}"
                                                action="{{ route('seller.resubmissions.resubmit', $car->id) }}" method="POST">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-success w-100"
                                                    onclick="confirmResubmit('{{ $car->id }}', '{{ $car->brand }} {{ $car->nama }}')">
                                                    <i class="fas fa-paper-plane me-1"></i>Ajukan Ulang
                                                </button>
                                            </form>
                                            <a href="{{ route('cars.edit', $car->id) }}"
                                                class="btn btn-sm btn-warning w-100 text-white">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <form id="delete-form-{{ $car->id }}" action="{{ route('cars.destroy', $car->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger w-100"
                                                    onclick="confirmDelete('{{ $car->id }}', '{{ $car->brand }} {{ $car->nama }}')">
                                                    <i class="fas fa-trash me-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
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
                <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                <h5>Tidak ada mobil yang ditolak</h5>
                <p>Semua mobil Anda telah disetujui atau belum ada pengajuan sebelumnya.</p>
            </div>
            <a href="{{ route('cars.create') }}" class="btn btn-danger mt-3">
                <i class="fas fa-plus me-2"></i>Tambah Mobil Baru
            </a>
    @endif

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function confirmResubmit(carId, carName) {
                    Swal.fire({
                        title: 'Ajukan Ulang Mobil?',
                        text: "Pastikan Anda sudah memperbaiki data mobil " + carName + " sesuai catatan penolakan.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Ya, Ajukan Ulang',
                        cancelButtonText: 'Cek Lagi',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('resubmit-form-' + carId).submit();
                        }
                    });
                }

                function confirmDelete(carId, carName) {
                    Swal.fire({
                        title: 'Hapus Mobil?',
                        html: "Mobil <strong>" + carName + "</strong> akan dihapus permanen. <br>Tindakan ini tidak dapat dibatalkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + carId).submit();
                        }
                    });
                }
            </script>
        @endpush
@endsection