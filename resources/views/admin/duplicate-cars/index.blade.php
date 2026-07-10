@extends('layouts.admin')

@section('content')
<style>
    .row-original {
        background-color: #dcfce7 !important; /* Green-100 */
        border-left: 4px solid #16a34a;
    }
    .row-duplicate {
        background-color: #fef9c3 !important; /* Yellow-100 */
        border-left: 4px solid #ca8a04;
    }
    .badge-original {
        background-color: #16a34a;
        color: white;
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .badge-duplicate {
        background-color: #ca8a04;
        color: white;
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .badge-duplikat-foto-ya {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 4px;
        font-weight: 700;
    }
    .badge-duplikat-foto-tidak {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 4px;
        font-weight: 700;
    }
    .nama-file-label {
        display: block;
        font-size: 0.72rem;
        color: #6b7280;
        word-break: break-all;
        max-width: 200px;
        line-height: 1.4;
    }
    .nama-file-label .prefix {
        color: #dc2626;
        font-weight: 700;
    }
    .foto-duplikat-section {
        background: linear-gradient(135deg, #fef2f2 0%, #fff1f2 100%);
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: 0;
        margin-top: 24px;
        overflow: hidden;
    }
    .foto-duplikat-header {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: #fff;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .foto-duplikat-header h5 {
        margin: 0;
        font-weight: 800;
        font-size: 16px;
    }
    .foto-dup-group {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 16px;
        background: #fff;
    }
    .foto-dup-group-header {
        background: linear-gradient(135deg, #1e293b, #334155);
        color: #fff;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .foto-dup-group-header h6 {
        margin: 0;
        font-weight: 700;
        font-size: 13px;
    }
    .foto-dup-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }
    .foto-dup-item:last-child {
        border-bottom: none;
    }
    .foto-dup-item:hover {
        background: #fafafa;
    }
    .foto-dup-item img {
        width: 64px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 2px solid #e5e7eb;
    }
    .foto-dup-info {
        flex: 1;
    }
    .foto-dup-car-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 13px;
    }
    .foto-dup-file-name {
        font-size: 11px;
        color: #6b7280;
        word-break: break-all;
    }
    .foto-dup-file-name .prefix {
        color: #dc2626;
        font-weight: 700;
    }
    .swal2-popup-duplicate-delete {
        border-radius: 5px !important;
        padding: 24px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25) !important;
        border: 1px solid #e9ecef !important;
        max-width: 520px !important;
    }
    .swal2-title-duplicate-delete {
        font-size: 20px !important;
        font-weight: 800 !important;
        color: #111827 !important;
        margin-bottom: 10px !important;
    }
    .swal2-html-duplicate-delete {
        font-size: 14px !important;
        color: #4b5563 !important;
        line-height: 1.6 !important;
        margin: 0 !important;
    }
    .swal2-confirm-duplicate-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626) !important;
        color: #fff !important;
        border: none !important;
        padding: 10px 18px !important;
        border-radius: 5px !important;
        font-weight: 700 !important;
    }
    .swal2-cancel-duplicate-delete {
        background: #f3f4f6 !important;
        color: #374151 !important;
        border: none !important;
        padding: 10px 18px !important;
        border-radius: 5px !important;
        font-weight: 700 !important;
    }
    .tab-duplikat {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
    }
    .tab-duplikat .tab-btn {
        padding: 10px 20px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        background: #fff;
        color: #374151;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tab-duplikat .tab-btn:hover {
        border-color: #dc2626;
        color: #dc2626;
    }
    .tab-duplikat .tab-btn.active {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        border-color: #dc2626;
        color: #fff;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
    .tab-content-dup {
        display: none;
    }
    .tab-content-dup.active {
        display: block;
    }
</style>

<h1 class="page-title mb-4">Deteksi Duplikat Mobil</h1>

<!-- Tab Navigation -->
<div class="tab-duplikat">
    <button class="tab-btn active" onclick="showTab('tab-data')" id="btn-tab-data">
        <i class="fas fa-layer-group"></i> Duplikat Data Mobil
        <span class="badge bg-warning text-dark">{{ count($duplicates) }}</span>
    </button>
    <button class="tab-btn" onclick="showTab('tab-foto')" id="btn-tab-foto">
        <i class="fas fa-images"></i> Duplikat Foto
        <span class="badge bg-danger">{{ count($fotoDuplikats) }}</span>
    </button>
</div>

<!-- Tab 1: Duplikat Data (Nama, Brand, Tahun) -->
<div class="tab-content-dup active" id="tab-data">
    <div class="info-card animate-fade-in mb-4">
        <div class="info-card-header">
            <h5 class="info-card-title">
                <i class="fas fa-exclamation-triangle me-2"></i>Mobil Terduplikasi (Nama, Brand, Tahun)
            </h5>
        </div>
        <div class="info-card-body">
            @if(count($duplicates) > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Ditemukan {{ count($duplicates) }} grup mobil dengan kombinasi Nama, Brand, dan Tahun yang sama.
                </div>

                @foreach($duplicates as $group)
                    <div class="card mb-4 shadow-sm" style="border: none; overflow: hidden;">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-layer-group me-2"></i>
                                <strong>{{ strtoupper($group['group_info']->brand) }} {{ $group['group_info']->nama }}</strong>
                                <span class="text-white-50">({{ $group['group_info']->tahun }})</span>
                            </h6>
                            <span class="badge bg-danger">{{ $group['group_info']->total }} Duplikat</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="100">Status</th>
                                            <th>ID</th>
                                            <th>Gambar</th>
                                            <th>Nama File</th>
                                            <th>Foto Duplikat</th>
                                            <th>Penjual</th>
                                            <th>Harga</th>
                                            <th>Status Post</th>
                                            <th>Tanggal Dibuat</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group['cars'] as $index => $car)
                                            <tr class="{{ $index === 0 ? 'row-original' : 'row-duplicate' }}">
                                                <td>
                                                    @if($index === 0)
                                                        <span class="badge badge-original">
                                                            <i class="fas fa-check-circle me-1"></i>Original
                                                        </span>
                                                    @else
                                                        <span class="badge badge-duplicate">
                                                            <i class="fas fa-copy me-1"></i>Duplikat
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>#{{ $car->id }}</td>
                                                <td>
                                                    @if($car->image && is_array($car->image) && count($car->image) > 0)
                                                        <img src="{{ asset('storage/' . $car->image[0]) }}" alt="Car" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                    @else
                                                        <div style="width: 60px; height: 40px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($car->image && is_array($car->image))
                                                        @foreach($car->image as $imgPath)
                                                            <span class="nama-file-label">
                                                                @php $fname = basename($imgPath); @endphp
                                                                @if(str_starts_with($fname, 'garasi62_'))
                                                                    <span class="prefix">garasi62_</span>{{ substr($fname, 9) }}
                                                                @else
                                                                    {{ $fname }}
                                                                @endif
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($car->is_foto_duplikat)
                                                        <span class="badge-duplikat-foto-ya">
                                                            <i class="fas fa-exclamation-triangle"></i> Duplikat
                                                        </span>
                                                    @else
                                                        <span class="badge-duplikat-foto-tidak">
                                                            <i class="fas fa-check-circle"></i> Unik
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($car->seller)
                                                        <div class="d-flex align-items-center">
                                                            <div class="fw-bold text-dark">{{ $car->seller->name }}</div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold">Rp {{ number_format($car->harga, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $car->status == 'approved' ? 'success' : ($car->status == 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($car->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="small text-muted">
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ $car->created_at->format('d M Y H:i') }}
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-light text-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="d-inline js-duplicate-delete" data-car-type="{{ $index === 0 ? 'original' : 'duplikat' }}" data-car-name="{{ $car->nama }}" data-car-brand="{{ $car->brand }}" data-car-year="{{ $car->tahun }}" data-car-id="{{ $car->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-light text-danger" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5>Tidak Ada Duplikat Data Ditemukan</h5>
                    <p class="text-muted">Semua data mobil unik berdasarkan Nama, Brand, dan Tahun.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Tab 2: Duplikat Foto -->
<div class="tab-content-dup" id="tab-foto">
    <div class="info-card animate-fade-in mb-4">
        <div class="info-card-header">
            <h5 class="info-card-title">
                <i class="fas fa-images me-2"></i>Deteksi Duplikat Foto
            </h5>
        </div>
        <div class="info-card-body">
            @if(count($fotoDuplikats) > 0)
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Ditemukan <strong>{{ count($fotoDuplikats) }}</strong> grup foto terduplikat atau terindikasi pencurian.
                </div>

                @foreach($fotoDuplikats as $idx => $group)
                    <div class="foto-dup-group">
                        <div class="foto-dup-group-header">
                            <h6>
                                @if(($group['type'] ?? 'hash') === 'prefix')
                                    <i class="fas fa-user-secret me-2"></i>
                                    Foto Terindikasi Pencurian (Awalan garasi62_)
                                @else
                                    <i class="fas fa-fingerprint me-2"></i>
                                    Grup Foto Duplikat #{{ $idx + 1 }}
                                @endif
                            </h6>
                            <span class="badge bg-danger">{{ $group['total'] }} Foto</span>
                        </div>
                        <div>
                            @foreach($group['entries'] as $entryIdx => $entry)
                                <div class="foto-dup-item">
                                    <img src="{{ asset('storage/' . $entry['image_path']) }}" alt="Foto Duplikat">
                                    <div class="foto-dup-info">
                                        <div class="foto-dup-car-name">
                                            @if(($group['type'] ?? 'hash') === 'prefix')
                                                <span class="badge badge-duplicate me-1">
                                                    <i class="fas fa-exclamation-triangle"></i> Pencurian
                                                </span>
                                            @else
                                                @if($entryIdx === 0)
                                                    <span class="badge badge-original me-1">
                                                        <i class="fas fa-check-circle"></i> Original
                                                    </span>
                                                @else
                                                    <span class="badge badge-duplicate me-1">
                                                        <i class="fas fa-copy"></i> Duplikat
                                                    </span>
                                                @endif
                                            @endif
                                            {{ strtoupper($entry['car']->brand) }} {{ $entry['car']->nama }}
                                            <span class="text-muted ms-1">(ID #{{ $entry['car']->id }})</span>
                                        </div>
                                        <div class="foto-dup-file-name">
                                            <i class="fas fa-file-image me-1"></i>
                                            Nama File:
                                            @if(str_starts_with($entry['file_name'], 'garasi62_'))
                                                <span class="prefix">garasi62_</span>{{ substr($entry['file_name'], 9) }}
                                            @else
                                                {{ $entry['file_name'] }}
                                            @endif
                                        </div>
                                        <div style="margin-top: 4px;">
                                            @if(($group['type'] ?? 'hash') === 'prefix')
                                                <span class="badge-duplikat-foto-ya">
                                                    <i class="fas fa-exclamation-triangle"></i> Foto Terindikasi Pencurian
                                                </span>
                                            @else
                                                @if($entry['car']->is_foto_duplikat)
                                                    <span class="badge-duplikat-foto-ya">
                                                        <i class="fas fa-exclamation-triangle"></i> Foto Duplikat
                                                    </span>
                                                @else
                                                    <span class="badge-duplikat-foto-tidak">
                                                        <i class="fas fa-check-circle"></i> Foto Unik
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    @if($entry['car']->seller)
                                        <div class="text-muted" style="font-size: 11px; min-width: 100px;">
                                            <i class="fas fa-user me-1"></i>{{ $entry['car']->seller->name }}
                                        </div>
                                    @endif
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('cars.edit', $entry['car']->id) }}" class="btn btn-light text-primary btn-sm" title="Edit Mobil">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('cars.destroy', $entry['car']->id) }}" method="POST" class="d-inline js-duplicate-delete" 
                                              data-car-type="duplikat foto" 
                                              data-car-name="{{ $entry['car']->nama }}" 
                                              data-car-brand="{{ $entry['car']->brand }}" 
                                              data-car-year="{{ $entry['car']->tahun }}" 
                                              data-car-id="{{ $entry['car']->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light text-danger btn-sm" title="Hapus Mobil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5>Tidak Ada Duplikat Foto Ditemukan</h5>
                    <p class="text-muted">Semua foto mobil unik, tidak ada gambar yang dipakai lebih dari satu mobil.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Tab switching
function showTab(tabId) {
    document.querySelectorAll('.tab-content-dup').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    if (tabId === 'tab-data') {
        document.getElementById('btn-tab-data').classList.add('active');
    } else {
        document.getElementById('btn-tab-foto').classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-duplicate-delete').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const carType = form.getAttribute('data-car-type');
            const carName = form.getAttribute('data-car-name');
            const carBrand = form.getAttribute('data-car-brand');
            const carYear = form.getAttribute('data-car-year');
            const carId = form.getAttribute('data-car-id');

            if (typeof Swal === 'undefined') {
                if (confirm(`Yakin ingin menghapus ${carType} ini?`)) {
                    form.submit();
                }
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: '<strong>Hapus Data Duplikat?</strong>',
                html: `<div style="text-align: left;">
                    <div style="display: flex; gap: 12px; align-items: center; background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 5px; padding: 12px; margin-bottom: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 5px; background: linear-gradient(135deg, #ef4444, #dc2626); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px;">
                            <i class="fas fa-car"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #111827; font-size: 14px;">${carBrand} ${carName}</div>
                            <div style="color: #6b7280; font-size: 12px;">Tahun ${carYear} · ID #${carId}</div>
                        </div>
                    </div>
                    <div style="background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412; padding: 10px 12px; border-radius: 5px; font-size: 13px;">
                        Data ${carType} akan dihapus permanen dan tidak dapat dikembalikan.
                    </div>
                </div>`,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Hapus',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                customClass: {
                    popup: 'swal2-popup-duplicate-delete',
                    confirmButton: 'swal2-confirm-duplicate-delete',
                    cancelButton: 'swal2-cancel-duplicate-delete',
                    title: 'swal2-title-duplicate-delete',
                    htmlContainer: 'swal2-html-duplicate-delete'
                },
                buttonsStyling: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
