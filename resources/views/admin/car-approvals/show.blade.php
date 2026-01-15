@extends('layouts.admin')

@section('content')
<h1 class="page-title mb-4">Detail Mobil - Persetujuan</h1>

<div class="row g-4">
    <!-- Car Details -->
    <div class="col-lg-8">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-car me-2"></i>Detail Mobil
                </h5>
                <span class="badge bg-warning">Menunggu Persetujuan</span>
            </div>

            <div class="car-detail-grid">
                <!-- Car Images -->
                <div class="car-images-section">
                    @if($car->image && is_array($car->image) && count($car->image) > 0)
                        <div class="main-image">
                            <img src="{{ asset('storage/' . $car->image[0]) }}" alt="{{ $car->brand }}" id="mainImage">
                        </div>
                        @if(count($car->image) > 1)
                        <div class="thumbnail-images">
                            @foreach($car->image as $index => $image)
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="Thumbnail {{ $index + 1 }}"
                                 class="thumbnail {{ $index === 0 ? 'active' : '' }}"
                                 data-image="{{ asset('storage/' . $image) }}">
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="no-image">
                            <i class="fas fa-car"></i>
                            <p>Tidak ada gambar</p>
                        </div>
                    @endif
                </div>

                <!-- Car Information -->
                <div class="car-info-section">
                    <h3 class="car-title">{{ strtoupper($car->brand) }} {{ $car->nama }}</h3>

                    <div class="car-price-section">
                        <span class="price">Rp {{ number_format($car->harga, 0, ',', '.') }}</span>
                        @if($car->tipe == 'rent')
                            <span class="price-unit">per hari</span>
                        @endif
                        <span class="car-type-badge {{ $car->tipe == 'rent' ? 'badge-rent' : 'badge-sale' }}">
                            {{ $car->tipe == 'rent' ? 'Disewakan' : 'Dijual' }}
                        </span>
                    </div>

                    <div class="car-specs">
                        <div class="spec-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Tahun: {{ $car->tahun }}</span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Kilometer: {{ number_format($car->kilometer, 0, ',', '.') }} km</span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-cogs"></i>
                            <span>Transmisi: {{ $car->transmisi }}</span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-gas-pump"></i>
                            <span>Kapasitas Mesin: {{ $car->kapasitasmesin }} CC</span>
                        </div>
                        @if($car->location)
                        <div class="spec-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Lokasi: {{ $car->location }}</span>
                        </div>
                        @endif
                    </div>

                    @if($car->description)
                    <div class="car-description">
                        <h5>Deskripsi</h5>
                        <p>{{ $car->description }}</p>
                    </div>
                    @endif

                    <!-- Features -->
                    @if($car->interior_features || $car->safety_features || $car->extra_features)
                    <div class="car-features">
                        <h5>Fitur Mobil</h5>
                        <div class="features-grid">
                            @if($car->interior_features && is_array($car->interior_features))
                            <div class="feature-group">
                                <h6>Fitur Interior</h6>
                                <ul>
                                    @foreach($car->interior_features as $feature)
                                    <li><i class="fas fa-check text-success"></i> {{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if($car->safety_features && is_array($car->safety_features))
                            <div class="feature-group">
                                <h6>Fitur Keselamatan</h6>
                                <ul>
                                    @foreach($car->safety_features as $feature)
                                    <li><i class="fas fa-shield-alt text-success"></i> {{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if($car->extra_features && is_array($car->extra_features))
                            <div class="feature-group">
                                <h6>Fitur Tambahan</h6>
                                <ul>
                                    @foreach($car->extra_features as $feature)
                                    <li><i class="fas fa-plus text-success"></i> {{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Panel -->
    <div class="col-lg-4">
        <!-- Seller Information -->
        <div class="info-card animate-slide-in-right mb-4">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-user me-2"></i>Informasi Penjual
                </h5>
            </div>
            @if($car->seller)
            <div class="seller-profile">
                <div class="seller-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="seller-details">
                    <h6>{{ $car->seller->name }}</h6>
                    <p class="mb-1">{{ $car->seller->email }}</p>
                    @if($car->seller->phone)
                    <p class="mb-0"><i class="fas fa-phone me-1"></i>{{ $car->seller->phone }}</p>
                    @endif
                    <small class="text-muted">Seller sejak {{ $car->seller->created_at->format('M Y') }}</small>
                </div>
            </div>
            @else
            <p class="text-muted">Informasi penjual tidak tersedia</p>
            @endif
        </div>

        <!-- Approval Actions -->
        <div class="info-card animate-slide-in-right">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-tasks me-2"></i>Aksi Persetujuan
                </h5>
            </div>

            <div class="d-grid gap-3">
                <form action="{{ route('admin.car-approvals.approve', $car) }}" method="POST" class="d-inline">
                    @csrf
                    <div class="mb-3">
                        <label for="approveNotes" class="form-label">Catatan Persetujuan (Opsional)</label>
                        <textarea class="form-control" id="approveNotes" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-check-circle me-2"></i>Setujui Mobil
                    </button>
                </form>

                <hr>

                <form action="{{ route('admin.car-approvals.reject', $car) }}" method="POST" class="d-inline">
                    @csrf
                    <div class="mb-3">
                        <label for="rejectNotes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectNotes" name="notes" rows="3" placeholder="Berikan alasan penolakan yang jelas..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-lg w-100">
                        <i class="fas fa-times-circle me-2"></i>Tolak Mobil
                    </button>
                </form>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.car-approvals.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Car Post Information -->
        <div class="info-card animate-slide-in-right">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-info-circle me-2"></i>Informasi Posting
                </h5>
            </div>
            <div class="post-info">
                <div class="info-item">
                    <span class="label">Tanggal Posting:</span>
                    <span class="value">{{ $car->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Terakhir Update:</span>
                    <span class="value">{{ $car->updated_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Status:</span>
                    <span class="value">
                        <span class="badge bg-warning">Menunggu Persetujuan</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.car-detail-grid {
    display: grid;
    gap: 30px;
}

.car-images-section {
    margin-bottom: 30px;
}

.main-image {
    width: 100%;
    height: 400px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 15px;
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumbnail-images {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.thumbnail {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.thumbnail.active {
    border-color: #dc2626;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
}

.no-image {
    width: 100%;
    height: 300px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.no-image i {
    font-size: 48px;
    margin-bottom: 15px;
}

.car-title {
    font-size: 28px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 15px;
}

.car-price-section {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.price {
    font-size: 32px;
    font-weight: 900;
    color: #dc2626;
}

.price-unit {
    color: #6b7280;
    font-size: 16px;
}

.car-type-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}

.badge-sale {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: #fff;
}

.badge-rent {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: #fff;
}

.car-specs {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.spec-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.spec-item i {
    color: #dc2626;
    width: 16px;
}

.car-description {
    margin-bottom: 25px;
}

.car-description h5 {
    color: #1a1a1a;
    margin-bottom: 10px;
}

.car-description p {
    color: #4b5563;
    line-height: 1.6;
}

.car-features h5 {
    color: #1a1a1a;
    margin-bottom: 15px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.feature-group h6 {
    color: #1a1a1a;
    margin-bottom: 10px;
    font-weight: 600;
}

.feature-group ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-group li {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
    font-size: 14px;
    color: #4b5563;
}

.seller-profile {
    display: flex;
    align-items: center;
    gap: 15px;
}

.seller-avatar {
    font-size: 48px;
    color: #dc2626;
}

.seller-details h6 {
    margin: 0 0 5px 0;
    font-weight: 600;
}

.post-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-item:last-child {
    border-bottom: none;
}

.label {
    font-weight: 500;
    color: #6b7280;
}

.value {
    font-weight: 600;
    color: #1a1a1a;
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
    .car-detail-grid {
        grid-template-columns: 1fr;
    }

    .car-price-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .car-specs {
        grid-template-columns: 1fr;
    }

    .features-grid {
        grid-template-columns: 1fr;
    }

    .main-image {
        height: 250px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle thumbnail clicks
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            // Add active class to clicked thumbnail
            this.classList.add('active');
            // Change main image
            document.getElementById('mainImage').src = this.dataset.image;
        });
    });
});
</script>
@endpush
@endsection
