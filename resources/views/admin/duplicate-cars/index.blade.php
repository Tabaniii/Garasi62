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
</style>

<h1 class="page-title mb-4">Deteksi Duplikat Mobil</h1>

<div class="info-card animate-fade-in mb-4">
    <div class="info-card-header">
        <h5 class="info-card-title">
            <i class="fas fa-exclamation-triangle me-2"></i>Mobil Terduplikasi
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
                                                    <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus {{ $index === 0 ? 'data original' : 'duplikat' }} ini?');">
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
                <h5>Tidak Ada Duplikat Ditemukan</h5>
                <p class="text-muted">Semua data mobil unik berdasarkan Nama, Brand, dan Tahun.</p>
            </div>
        @endif
    </div>
</div>
@endsection
