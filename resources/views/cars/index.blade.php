@extends('template.temp')

@section('content')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Cars Management Section Begin -->
<section class="py-5">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-danger mb-2">Kelola Mobil Saya</h2>
                        <p class="text-muted mb-0">Daftar mobil yang telah Anda tambahkan</p>
                    </div>
                    <a href="{{ route('cars.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus me-2"></i>Tambah Mobil Baru
                    </a>
                </div>
            </div>
        </div>

        @if($cars->count() > 0)
        <div class="row g-4">
            @foreach($cars as $car)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            @if($car->image && is_array($car->image) && count($car->image) > 0)
                            <div class="car__item__pic__slider owl-carousel">
                                @foreach($car->image as $imagePath)
                                <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $car->brand }}" style="height: 200px; width: 100%; object-fit: cover; border-radius: 8px;">
                                @endforeach
                            </div>
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa fa-car text-muted" style="font-size: 48px;"></i>
                            </div>
                            @endif
                        </div>
                        <h5 class="card-title mb-2 fw-bold">{{ $car->brand }}</h5>
                        <div class="mb-2">
                            <span class="badge {{ $car->tipe == 'rent' ? 'bg-info' : 'bg-success' }} mb-2">
                                {{ $car->tipe == 'rent' ? 'For Rent' : 'For Sale' }}
                            </span>
                            <small class="text-muted d-block">Tahun: <strong>{{ $car->tahun }}</strong></small>
                            <small class="text-muted d-block">Kilometer: <strong>{{ $car->kilometer }} km</strong></small>
                            <small class="text-muted d-block">Transmisi: <strong>{{ $car->transmisi }}</strong></small>
                            <small class="text-muted d-block">Kapasitas Mesin: <strong>{{ $car->kapasitasmesin }}</strong></small>
                            <small class="text-muted d-block">Harga: <strong>Rp {{ number_format($car->harga, 0, ',', '.') }}</strong></small>
                            <small class="text-muted d-block">Metode: <strong>{{ $car->metode }}</strong></small>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-sm btn-warning flex-fill">
                                <i class="fa fa-edit me-1"></i>Edit
                            </a>
                            <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mobil ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100">
                                    <i class="fa fa-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5 text-center">
                        <i class="fa fa-car text-muted mb-3" style="font-size: 64px;"></i>
                        <h4 class="text-muted mb-3">Belum ada mobil</h4>
                        <p class="text-muted mb-4">Mulai dengan menambahkan mobil pertama Anda</p>
                        <a href="{{ route('cars.create') }}" class="btn btn-danger">
                            <i class="fa fa-plus me-2"></i>Tambah Mobil Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row mt-4">
            <div class="col-12">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</section>
<!-- Cars Management Section End -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

