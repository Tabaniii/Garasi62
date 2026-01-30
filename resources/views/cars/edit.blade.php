@extends('layouts.admin')

@section('header-title', 'Edit Mobil')

@section('content')
<div class="page-header-section mb-5">
    <div class="page-header-content">
        <div class="page-header-text">
            <div class="page-title-wrapper">
                <h1 class="page-title">Edit Mobil</h1>
            </div>
            <p class="page-subtitle">
                <i class="fas fa-info-circle me-2"></i>Edit informasi mobil di bawah ini
            </p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('cars.index') }}" class="btn-back-dashboard">
                <i class="fas fa-arrow-left me-2"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-10 mx-auto">
        <form action="{{ route('cars.update', $car->id) }}" method="POST" enctype="multipart/form-data" id="carForm">
            @csrf
            @method('PUT')
            
            <!-- Tipe Mobil -->
            <div class="info-card animate-fade-in mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-tag me-2"></i>Tipe Mobil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group-modern">
                        <label for="tipe" class="form-label-modern">
                            Tipe <span class="text-danger">*</span>
                        </label>
                        <select class="form-control-modern @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                            <option value="">Pilih Tipe</option>
                            <option value="rent" {{ old('tipe', $car->tipe) == 'rent' ? 'selected' : '' }}>Rent (Sewa)</option>
                            <option value="buy" {{ old('tipe', $car->tipe) == 'buy' ? 'selected' : '' }}>Buy (Beli)</option>
                        </select>
                        @error('tipe')
                            <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Gambar Mobil -->
            <div class="info-card animate-fade-in mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-images me-2"></i>Gambar Mobil
                    </h5>
                    <span class="badge bg-danger">Maksimal 6 gambar</span>
                </div>
                <div class="card-body">
                    <!-- Existing Images -->
                    @php
                        $existingImages = is_array($car->image) ? $car->image : (is_string($car->image) ? json_decode($car->image, true) : []);
                        if (!is_array($existingImages)) $existingImages = [];
                    @endphp
                    
                    @if(count($existingImages) > 0)
                    <div class="mb-4">
                        <label class="form-label-modern">Gambar yang Sudah Ada</label>
                        <div id="existingImages" class="image-grid-modern">
                            @foreach($existingImages as $index => $imagePath)
                                <div class="image-item-modern existing-image-item" data-image-index="{{ $index }}">
                                    <input type="hidden" name="existing_images[]" value="{{ $imagePath }}">
                                    <div class="image-wrapper-modern">
                                        <img src="{{ Storage::url($imagePath) }}" alt="Gambar {{ $index + 1 }}" class="image-preview-modern">
                                        <div class="image-label" style="background: linear-gradient(135deg, #3b82f6, #60a5fa);">
                                            <i class="fas fa-image"></i> Gambar {{ $index + 1 }}
                                        </div>
                                        <button type="button" class="btn-remove-image" onclick="removeExistingImage(this)" title="Hapus gambar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- New Images Upload -->
                    <div class="upload-section-modern">
                        <label for="images" class="upload-label-modern">
                            <i class="fas fa-plus-circle me-2"></i>
                            <span>Tambah Gambar Baru (Opsional)</span>
                        </label>
                        <input type="file" class="file-input-modern @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple>
                        @error('images')
                            <div class="error-message mt-2"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="error-message mt-2"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                        @enderror
                        <div class="upload-hint mt-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Pilih gambar baru untuk ditambahkan (Format: JPG, PNG, GIF, WEBP | Maks 5MB per gambar)
                        </div>
                        <div id="imagePreview" class="image-grid-modern mt-3"></div>
                    </div>
                </div>
            </div>

            <!-- Informasi Dasar -->
            <div class="info-card animate-fade-in mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="brand" class="form-label-modern">
                                    Brand/Merek <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control-modern @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $car->brand) }}" placeholder="Contoh: Toyota, Honda" required maxlength="20">
                                @error('brand')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="nama" class="form-label-modern">
                                    Nama Mobil <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control-modern @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $car->nama ?? '') }}" placeholder="Contoh: Avanza, Civic, Camry" required maxlength="100">
                                @error('nama')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spesifikasi -->
            <div class="info-card animate-fade-in mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-cog me-2"></i>Spesifikasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="tahun" class="form-label-modern">
                                    Tahun <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control-modern @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="{{ old('tahun', $car->tahun) }}" placeholder="2020" required maxlength="4" pattern="[0-9]{4}">
                                @error('tahun')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="kilometer" class="form-label-modern">
                                    Kilometer <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control-modern @error('kilometer') is-invalid @enderror" id="kilometer" name="kilometer" value="{{ old('kilometer', $car->kilometer) }}" placeholder="50000" required maxlength="6" pattern="[0-9]+">
                                @error('kilometer')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="transmisi" class="form-label-modern">
                                    Transmisi <span class="text-danger">*</span>
                                </label>
                                <select class="form-control-modern @error('transmisi') is-invalid @enderror" id="transmisi" name="transmisi" required>
                                    <option value="">Pilih Transmisi</option>
                                    <option value="Manual" {{ old('transmisi', $car->transmisi) == 'Manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="Automatic" {{ old('transmisi', $car->transmisi) == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                    <option value="CVT" {{ old('transmisi', $car->transmisi) == 'CVT' ? 'selected' : '' }}>CVT</option>
                                </select>
                                @error('transmisi')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="kapasitasmesin" class="form-label-modern">
                                    Kapasitas Mesin <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control-modern @error('kapasitasmesin') is-invalid @enderror" id="kapasitasmesin" name="kapasitasmesin" value="{{ old('kapasitasmesin', $car->kapasitasmesin) }}" placeholder="Contoh: 1500cc, 2000cc" required maxlength="50">
                                @error('kapasitasmesin')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="metode" class="form-label-modern">
                                    Metode <span class="text-danger">*</span>
                                </label>
                                <select class="form-control-modern @error('metode') is-invalid @enderror" id="metode" name="metode" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="Cash" {{ old('metode', $car->metode) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Kredit" {{ old('metode', $car->metode) == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                                </select>
                                @error('metode')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group-modern mt-3">
                        <label for="harga" class="form-label-modern">
                            Harga <span class="text-danger">*</span>
                        </label>
                        <div class="input-group-modern">
                            <span class="input-prefix">Rp</span>
                            <input type="text" class="form-control-modern @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $car->harga) }}" placeholder="250000000" required maxlength="10" pattern="[0-9]+">
                        </div>
                        @error('harga')
                            <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Masukkan harga tanpa titik atau koma</div>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="info-card animate-fade-in mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                    </h5>
                    <span class="badge bg-secondary">Opsional</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="stock" class="form-label-modern">Stock Number</label>
                                <input type="text" class="form-control-modern @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $car->stock) }}" placeholder="K99D10459934" maxlength="50">
                                @error('stock')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="vin" class="form-label-modern">VIN Number</label>
                                <input type="text" class="form-control-modern @error('vin') is-invalid @enderror" id="vin" name="vin" value="{{ old('vin', $car->vin) }}" placeholder="3VWKM245686" maxlength="50">
                                @error('vin')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="msrp" class="form-label-modern">MSRP (Harga Asli)</label>
                                <div class="input-group-modern">
                                    <span class="input-prefix">Rp</span>
                                    <input type="text" class="form-control-modern @error('msrp') is-invalid @enderror" id="msrp" name="msrp" value="{{ old('msrp', $car->msrp) }}" placeholder="120000000" maxlength="15" pattern="[0-9]+">
                                </div>
                                @error('msrp')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="dealer_discounts" class="form-label-modern">Dealer Discounts</label>
                                <div class="input-group-modern">
                                    <span class="input-prefix">Rp</span>
                                    <input type="text" class="form-control-modern @error('dealer_discounts') is-invalid @enderror" id="dealer_discounts" name="dealer_discounts" value="{{ old('dealer_discounts', $car->dealer_discounts) }}" placeholder="3000000" maxlength="15" pattern="[0-9]+">
                                </div>
                                @error('dealer_discounts')
                                    <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group-modern mt-3">
                        <label for="description" class="form-label-modern">Deskripsi / General Information</label>
                        <textarea class="form-control-modern @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Masukkan deskripsi umum tentang mobil...">{{ old('description', $car->description) }}</textarea>
                        @error('description')
                            <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-modern mt-3">
                        <label for="location" class="form-label-modern">Lokasi Kendaraan</label>
                        <input type="text" class="form-control-modern @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $car->location) }}" placeholder="Jakarta, Bandung, dll" maxlength="255">
                        @error('location')
                            <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="info-card animate-fade-in mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-star me-2"></i>Features
                    </h5>
                    <span class="badge bg-secondary">Opsional</span>
                </div>
                <div class="card-body">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Interior Features</label>
                        <div class="features-grid">
                            @php
                                $interiorFeatures = old('interior_features', $car->interior_features ?? []);
                                if (!is_array($interiorFeatures)) $interiorFeatures = [];
                            @endphp
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" class="form-control-modern feature-input" name="interior_features[]" value="{{ $interiorFeatures[$i] ?? '' }}" placeholder="Feature {{ $i + 1 }} (kosongkan jika tidak ada)">
                            @endfor
                        </div>
                    </div>

                    <div class="form-group-modern mt-3">
                        <label class="form-label-modern">Safety Features</label>
                        <div class="features-grid">
                            @php
                                $safetyFeatures = old('safety_features', $car->safety_features ?? []);
                                if (!is_array($safetyFeatures)) $safetyFeatures = [];
                            @endphp
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" class="form-control-modern feature-input" name="safety_features[]" value="{{ $safetyFeatures[$i] ?? '' }}" placeholder="Feature {{ $i + 1 }} (kosongkan jika tidak ada)">
                            @endfor
                        </div>
                    </div>

                    <div class="form-group-modern mt-3">
                        <label class="form-label-modern">Extra Features</label>
                        <div class="features-grid">
                            @php
                                $extraFeatures = old('extra_features', $car->extra_features ?? []);
                                if (!is_array($extraFeatures)) $extraFeatures = [];
                            @endphp
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" class="form-control-modern feature-input" name="extra_features[]" value="{{ $extraFeatures[$i] ?? '' }}" placeholder="Feature {{ $i + 1 }} (kosongkan jika tidak ada)">
                            @endfor
                        </div>
                    </div>

                    <div class="form-group-modern mt-3">
                        <label for="technical_specs" class="form-label-modern">Technical Specifications</label>
                        <textarea class="form-control-modern @error('technical_specs') is-invalid @enderror" id="technical_specs" name="technical_specs" rows="5" placeholder="Masukkan spesifikasi teknis mobil...">{{ old('technical_specs', $car->technical_specs) }}</textarea>
                        @error('technical_specs')
                            <div class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions-modern mt-4">
                <button type="submit" class="btn-submit-modern" id="submitBtn">
                    <i class="fas fa-save me-2"></i>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="{{ route('cars.index') }}" class="btn-cancel-modern">
                    <i class="fas fa-times me-2"></i>
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
</div>

<style>
/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-fade-in {
    opacity: 1 !important;
    animation: fadeInUp 0.5s ease-out forwards;
    animation-fill-mode: both;
}

/* Ensure content is visible even if animation doesn't load */
.info-card {
    opacity: 1 !important;
    visibility: visible !important;
}

.info-card.animate-fade-in {
    opacity: 1 !important;
    visibility: visible !important;
}

.page-header-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #f1f5f9 100%) !important;
    padding: 50px 45px !important;
    border-radius: 16px !important;
    border: 1px solid rgba(220, 38, 38, 0.1) !important;
    margin-bottom: 40px !important;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.9) !important;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.page-header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, #dc2626, #ef4444, #f87171, #ef4444, #dc2626, #dc2626);
    background-size: 300% 100%;
    animation: shimmer 4s ease-in-out infinite;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
}

.page-header-section::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(220, 38, 38, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.page-header-content {
    display: flex !important;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
    position: relative;
    z-index: 1;
}

.page-title-wrapper {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.page-title-wrapper::before {
    content: '';
    width: 5px;
    height: 50px;
    background: linear-gradient(180deg, #dc2626, #ef4444);
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
}

.page-header-section .page-title {
    font-size: 42px !important;
    font-weight: 900 !important;
    background: linear-gradient(135deg, #1a1a1a 0%, #dc2626 50%, #1a1a1a 100%);
    background-size: 200% 100%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 !important;
    letter-spacing: -1px;
    animation: shimmer 3s ease-in-out infinite;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-subtitle {
    font-size: 16px;
    color: #64748b;
    margin: 0;
    font-weight: 500;
    padding-left: 25px;
    position: relative;
}

.page-subtitle i {
    color: #dc2626;
    margin-right: 10px;
    animation: pulse 2s ease-in-out infinite;
}

.info-card {
    background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
    border-radius: 16px;
    padding: 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04), inset 0 1px 0 rgba(255,255,255,0.9);
    border: 1px solid rgba(220, 38, 38, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: visible;
    position: relative;
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.3), transparent);
    opacity: 0;
    transition: opacity 0.4s;
}

.info-card:hover {
    box-shadow: 0 12px 32px rgba(220, 38, 38, 0.15), 0 4px 8px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.9);
    transform: translateY(-4px);
    border-color: rgba(220, 38, 38, 0.2);
}

.info-card:hover::before {
    opacity: 1;
}

.info-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 28px;
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    border-bottom: 2px solid rgba(220, 38, 38, 0.1);
    position: relative;
}

.info-card-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #dc2626, #ef4444);
    border-radius: 0 3px 3px 0;
}

.info-card-title {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.3px;
}

.info-card-title i {
    color: #dc2626;
    font-size: 22px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(239, 68, 68, 0.1));
    border-radius: 8px;
    transition: all 0.3s;
}

.info-card:hover .info-card-title i {
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.2), rgba(239, 68, 68, 0.2));
    transform: scale(1.1) rotate(5deg);
}

.card-body {
    padding: 28px;
    background: #ffffff;
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
}

.form-group-modern {
    margin-bottom: 24px;
    position: relative;
}

.form-group-modern:last-child {
    margin-bottom: 0;
}

.form-label-modern {
    display: block;
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    letter-spacing: -0.2px;
    position: relative;
    padding-left: 8px;
}

.form-label-modern::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 18px;
    background: linear-gradient(180deg, #dc2626, #ef4444);
    border-radius: 2px;
    opacity: 0;
    transition: opacity 0.3s;
}

.form-group-modern:focus-within .form-label-modern::before {
    opacity: 1;
}

.form-control-modern {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
    color: #1e293b;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
}

.form-control-modern:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.form-control-modern:focus {
    outline: none;
    border-color: #dc2626;
    box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.12), 0 4px 12px rgba(220, 38, 38, 0.15);
    background: #ffffff;
    transform: translateY(-1px);
}

.form-control-modern.is-invalid {
    border-color: #dc2626;
    box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
}

select.form-control-modern {
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23dc2626' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat !important;
    background-size: 16px 16px !important;
    background-position: right 18px center !important;
    padding-right: 50px;
    background-color: #ffffff !important;
}

select.form-control-modern:hover {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23ef4444' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat !important;
    background-size: 16px 16px !important;
    background-position: right 18px center !important;
}

select.form-control-modern:focus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23dc2626' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat !important;
    background-size: 16px 16px !important;
    background-position: right 18px center !important;
}

.input-group-modern {
    display: flex;
    align-items: center;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.input-group-modern:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.input-prefix {
    padding: 14px 18px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    color: #64748b;
    font-weight: 800;
    font-size: 16px;
    border-right: 2px solid #e2e8f0;
    white-space: nowrap;
    transition: all 0.3s;
}

.input-group-modern:hover .input-prefix {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    color: #475569;
}

.input-group-modern .form-control-modern {
    border: none;
    flex: 1;
    box-shadow: none;
    padding-left: 12px;
}

.input-group-modern .form-control-modern:hover {
    box-shadow: none;
    transform: none;
}

.input-group-modern:focus-within {
    border-color: #dc2626;
    box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.12), 0 4px 12px rgba(220, 38, 38, 0.15);
    transform: translateY(-1px);
}

.input-group-modern:focus-within .input-prefix {
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(239, 68, 68, 0.1));
    color: #dc2626;
    border-right-color: rgba(220, 38, 38, 0.2);
}

.form-hint {
    font-size: 13px;
    color: #64748b;
    margin-top: 8px;
    padding-left: 4px;
    font-weight: 500;
}

.error-message {
    color: #dc2626;
    font-size: 13px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    padding: 10px 14px;
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-left: 4px solid #dc2626;
    border-radius: 8px;
    font-weight: 600;
}

.upload-section-modern {
    margin-top: 10px;
}

.upload-label-modern {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 32px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 3px dashed #cbd5e1;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 700;
    font-size: 16px;
    color: #475569;
    margin-bottom: 18px;
    position: relative;
    overflow: hidden;
}

.upload-label-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.1), transparent);
    transition: left 0.5s;
}

.upload-label-modern:hover {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-color: #dc2626;
    color: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 38, 38, 0.2);
}

.upload-label-modern:hover::before {
    left: 100%;
}

.upload-label-modern i {
    font-size: 20px;
    transition: transform 0.3s;
}

.upload-label-modern:hover i {
    transform: scale(1.2) rotate(90deg);
}

.file-input-modern {
    display: none;
}

.upload-hint {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 10px;
    border-left: 4px solid #3b82f6;
}

.image-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.image-item-modern {
    position: relative;
    opacity: 1 !important;
    visibility: visible !important;
    animation: slideInRight 0.4s ease-out forwards;
    animation-fill-mode: both;
}

.image-wrapper-modern {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    border: 3px solid #e2e8f0;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    width: 100%;
    height: 200px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.image-wrapper-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(220, 38, 38, 0), rgba(220, 38, 38, 0));
    transition: background 0.4s;
    z-index: 1;
    pointer-events: none;
}

.image-wrapper-modern:hover {
    border-color: #dc2626;
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 32px rgba(220, 38, 38, 0.3), 0 4px 8px rgba(0, 0, 0, 0.1);
}

.image-wrapper-modern:hover::before {
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(239, 68, 68, 0.1));
}

.image-preview-modern {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.4s;
}

.image-wrapper-modern:hover .image-preview-modern {
    transform: scale(1.1);
}

.image-label {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(180deg, transparent, rgba(0, 0, 0, 0.85));
    color: #fff;
    padding: 10px;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    z-index: 2;
    backdrop-filter: blur(10px);
}

.btn-remove-image {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(220, 38, 38, 0.95);
    color: #fff;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s;
    z-index: 10;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
}

.btn-remove-image:hover {
    background: #dc2626;
    transform: scale(1.15) rotate(90deg);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.5);
}

.existing-image-item.removed {
    opacity: 0.5;
    pointer-events: none;
}

.existing-image-item.removed .image-wrapper-modern {
    border-color: #dc2626;
    position: relative;
}

.existing-image-item.removed .image-wrapper-modern::after {
    content: 'Akan Dihapus';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(220, 38, 38, 0.95);
    color: #fff;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 800;
    font-size: 13px;
    z-index: 20;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.feature-input {
    margin-bottom: 0;
}

textarea.form-control-modern {
    resize: vertical;
    min-height: 120px;
}

.form-actions-modern {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
}

.btn-submit-modern,
.btn-cancel-modern {
    flex: 1;
    padding: 18px 32px;
    border-radius: 14px;
    font-size: 17px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    letter-spacing: -0.3px;
}

.btn-submit-modern::before,
.btn-cancel-modern::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-submit-modern:hover::before,
.btn-cancel-modern:hover::before {
    width: 300px;
    height: 300px;
}

.btn-submit-modern {
    background: linear-gradient(135deg, #dc2626, #ef4444, #f87171);
    background-size: 200% 100%;
    color: #fff;
    box-shadow: 0 8px 24px rgba(220, 38, 38, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.2);
    animation: shimmer 3s ease-in-out infinite;
}

.btn-submit-modern:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 12px 32px rgba(220, 38, 38, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.3);
    color: #fff;
}

.btn-submit-modern:active {
    transform: translateY(-2px) scale(0.98);
}

.btn-submit-modern i {
    transition: transform 0.3s;
}

.btn-submit-modern:hover i {
    transform: rotate(360deg);
}

.btn-cancel-modern {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    color: #475569;
    border: 2px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.btn-cancel-modern:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-color: #cbd5e1;
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    color: #334155;
    text-decoration: none;
}

.btn-cancel-modern:active {
    transform: translateY(-2px) scale(0.98);
}

.btn-back-dashboard {
    display: inline-flex !important;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    background: linear-gradient(135deg, #64748b, #475569);
    color: #fff;
    text-decoration: none;
    border-radius: 14px;
    font-weight: 800;
    font-size: 16px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-back-dashboard::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-back-dashboard:hover::before {
    width: 300px;
    height: 300px;
}

.btn-back-dashboard:hover {
    background: linear-gradient(135deg, #475569, #334155);
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 8px 24px rgba(100, 116, 139, 0.4);
    color: #fff;
}

.btn-back-dashboard i {
    transition: transform 0.3s;
}

.btn-back-dashboard:hover i {
    transform: translateX(-4px);
}

.alert {
    border-radius: 16px;
    border: none;
    padding: 20px 24px;
    margin-bottom: 28px;
    box-shadow: 0 4px 16px rgba(220, 38, 38, 0.15);
    opacity: 1 !important;
    visibility: visible !important;
    animation: slideInRight 0.4s ease-out forwards;
    animation-fill-mode: both;
}

.alert-danger {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    color: #991b1b;
    border-left: 5px solid #dc2626;
    position: relative;
    overflow: hidden;
}

.alert-danger::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(180deg, #dc2626, #ef4444);
    animation: pulse 2s ease-in-out infinite;
}

.alert-danger ul {
    margin-top: 12px;
    padding-left: 24px;
    line-height: 1.8;
}

.alert-danger i {
    animation: pulse 2s ease-in-out infinite;
}

.badge {
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.3px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    animation: pulse 2s ease-in-out infinite;
}

.badge.bg-danger {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #64748b, #475569) !important;
}

@media (max-width: 991px) {
    .page-header-content {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767px) {
    .image-grid-modern {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-actions-modern {
        flex-direction: column;
    }
    
    .page-header-section .page-title {
        font-size: 32px !important;
    }
}
</style>

@push('scripts')
<script>
// Remove existing image
function removeExistingImage(btn) {
    const item = btn.closest('.existing-image-item');
    if (item) {
        item.classList.add('removed');
        const hiddenInput = item.querySelector('input[type="hidden"]');
        if (hiddenInput) {
            hiddenInput.name = 'removed_images[]';
        }
    }
}

// Preview new images before upload
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const existingImages = document.querySelectorAll('.existing-image-item:not(.removed)').length;
    const maxTotal = 6;
    const maxNew = maxTotal - existingImages;
    
    const files = e.target.files;
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    
    if (files.length > maxNew) {
        alert(`Maksimal ${maxNew} gambar baru yang dapat ditambahkan (total maksimal ${maxTotal} gambar).`);
        e.target.value = '';
        return;
    }
    
    // Clear previous previews
    preview.innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Validasi tipe file
        if (!allowedTypes.includes(file.type)) {
            alert(`File ${i + 1}: Format tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.`);
            e.target.value = '';
            preview.innerHTML = '';
            return;
        }
        
        // Validasi ukuran file
        if (file.size > maxSize) {
            alert(`File ${i + 1}: Ukuran file terlalu besar. Maksimal 5MB per gambar.`);
            e.target.value = '';
            preview.innerHTML = '';
            return;
        }
        
        // Preview gambar
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const item = document.createElement('div');
                item.className = 'image-item-modern';
                item.innerHTML = `
                    <div class="image-wrapper-modern">
                        <img src="${e.target.result}" alt="Preview ${i + 1}" class="image-preview-modern">
                        <div class="image-label" style="background: linear-gradient(135deg, #10b981, #34d399);">
                            <i class="fas fa-check-circle"></i> Baru (${(file.size / 1024 / 1024).toFixed(2)} MB)
                        </div>
                    </div>
                `;
                preview.appendChild(item);
            };
            reader.readAsDataURL(file);
        }
    }
});

// File input click handler
document.querySelector('.upload-label-modern').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('images').click();
});

// Form submit handler
document.getElementById('carForm').addEventListener('submit', function(e) {
    const existingImages = document.querySelectorAll('.existing-image-item:not(.removed)').length;
    const newImages = document.getElementById('images').files.length;
    const totalImages = existingImages + newImages;
    
    if (totalImages > 6) {
        e.preventDefault();
        alert('Total gambar tidak boleh lebih dari 6.');
        return false;
    }
    
    if (totalImages < 1) {
        e.preventDefault();
        alert('Minimal harus ada 1 gambar.');
        return false;
    }
    
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span>Menyimpan...</span>';
});
</script>
@endpush
@endsection
