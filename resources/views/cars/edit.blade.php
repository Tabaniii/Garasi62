@extends('template.temp')

@section('content')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Edit Car Section Begin -->
<section class="edit-car-section py-5">
    <div class="container">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="page-header-modern">
                    <div class="header-icon">
                        <i class="fa fa-edit"></i>
                    </div>
                    <div class="header-content">
                        <h1 class="page-title">Edit Mobil</h1>
                        <p class="page-subtitle">Ubah informasi mobil di bawah ini</p>
                    </div>
                    <a href="{{ route('cars.index') }}" class="btn-back">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="form-card-modern">
                    <div class="form-card-header">
                        <h3><i class="fa fa-car"></i> Informasi Mobil</h3>
                    </div>
                    <div class="form-card-body">
                        <form action="{{ route('cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Tipe Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4><i class="fa fa-tag"></i> Tipe Mobil</h4>
                                </div>
                                <div class="form-group-modern">
                                    <label for="tipe" class="form-label-modern">
                                        Tipe <span class="required">*</span>
                                    </label>
                                    <select class="form-control-modern @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                                        <option value="rent" {{ old('tipe', $car->tipe) == 'rent' ? 'selected' : '' }}>Rent (Sewa)</option>
                                        <option value="buy" {{ old('tipe', $car->tipe) == 'buy' ? 'selected' : '' }}>Buy (Beli)</option>
                                    </select>
                                    @error('tipe')
                                        <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4><i class="fa fa-images"></i> Gambar Mobil</h4>
                                    <span class="section-badge">Maksimal 6 gambar</span>
                                </div>
                                
                                @if($car->image && is_array($car->image) && count($car->image) > 0)
                                <div class="existing-images-section">
                                    <div class="section-label">
                                        <i class="fa fa-image"></i> Gambar yang ada ({{ count($car->image) }} gambar)
                                    </div>
                                    <div id="existingImages" class="image-grid-modern">
                                        @foreach($car->image as $index => $imagePath)
                                        <div class="image-item-modern existing-image-item" data-image="{{ $imagePath }}">
                                            <div class="image-wrapper-modern">
                                                @if(file_exists(public_path('storage/' . $imagePath)))
                                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Gambar {{ $index + 1 }}" class="image-preview-modern" onclick="window.open('{{ asset('storage/' . $imagePath) }}', '_blank')">
                                                @else
                                                    <div class="image-placeholder">
                                                        <i class="fa fa-image"></i>
                                                        <p>Gambar tidak ditemukan</p>
                                                    </div>
                                                @endif
                                                <button type="button" class="btn-remove-image remove-existing-image" data-image="{{ $imagePath }}" title="Hapus gambar">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <div class="image-label">
                                                    <i class="fa fa-image"></i> Gambar {{ $index + 1 }}
                                                </div>
                                            </div>
                                            <input type="hidden" name="existing_images[]" value="{{ $imagePath }}" class="existing-image-input">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <div class="upload-section-modern">
                                    <label for="images" class="upload-label-modern">
                                        <i class="fa fa-plus-circle"></i>
                                        <span>Tambah Gambar Baru (Opsional)</span>
                                    </label>
                                    <input type="file" class="file-input-modern @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple>
                                    @error('images')
                                        <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                    @error('images.*')
                                        <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                    <div class="upload-hint">
                                        <i class="fa fa-info-circle"></i>
                                        Format: JPG, PNG, GIF, WEBP | Maks 5MB per gambar | Total maksimal 6 gambar
                                    </div>
                                    <div id="imagePreview" class="image-grid-modern mt-3"></div>
                                </div>
                            </div>

                            <!-- Basic Info Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4><i class="fa fa-info-circle"></i> Informasi Dasar</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="brand" class="form-label-modern">
                                                Brand/Merek <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control-modern @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $car->brand) }}" placeholder="Contoh: Toyota, Honda" required maxlength="20">
                                            @error('brand')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="nama" class="form-label-modern">
                                                Nama Mobil <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control-modern @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $car->nama ?? '') }}" placeholder="Contoh: Avanza, Civic, Camry" required maxlength="100">
                                            @error('nama')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Specifications Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4><i class="fa fa-cog"></i> Spesifikasi</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label for="tahun" class="form-label-modern">
                                                Tahun <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control-modern @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="{{ old('tahun', $car->tahun) }}" placeholder="2020" required maxlength="4">
                                            @error('tahun')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label for="kilometer" class="form-label-modern">
                                                Kilometer <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control-modern @error('kilometer') is-invalid @enderror" id="kilometer" name="kilometer" value="{{ old('kilometer', $car->kilometer) }}" placeholder="50000" required maxlength="6">
                                            @error('kilometer')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label for="transmisi" class="form-label-modern">
                                                Transmisi <span class="required">*</span>
                                            </label>
                                            <select class="form-control-modern @error('transmisi') is-invalid @enderror" id="transmisi" name="transmisi" required>
                                                <option value="">Pilih Transmisi</option>
                                                <option value="Manual" {{ old('transmisi', $car->transmisi) == 'Manual' ? 'selected' : '' }}>Manual</option>
                                                <option value="Automatic" {{ old('transmisi', $car->transmisi) == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                                <option value="CVT" {{ old('transmisi', $car->transmisi) == 'CVT' ? 'selected' : '' }}>CVT</option>
                                            </select>
                                            @error('transmisi')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="kapasitasmesin" class="form-label-modern">
                                                Kapasitas Mesin <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control-modern @error('kapasitasmesin') is-invalid @enderror" id="kapasitasmesin" name="kapasitasmesin" value="{{ old('kapasitasmesin', $car->kapasitasmesin) }}" placeholder="Contoh: 1500cc, 2000cc" required maxlength="50">
                                            @error('kapasitasmesin')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="metode" class="form-label-modern">
                                                Metode <span class="required">*</span>
                                            </label>
                                            <select class="form-control-modern @error('metode') is-invalid @enderror" id="metode" name="metode" required>
                                                <option value="">Pilih Metode</option>
                                                <option value="Cash" {{ old('metode', $car->metode) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="Kredit" {{ old('metode', $car->metode) == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                                            </select>
                                            @error('metode')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group-modern">
                                    <label for="harga" class="form-label-modern">
                                        Harga <span class="required">*</span>
                                    </label>
                                    <div class="input-group-modern">
                                        <span class="input-prefix">Rp</span>
                                        <input type="text" class="form-control-modern @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $car->harga) }}" placeholder="250000000" required maxlength="10">
                                    </div>
                                    @error('harga')
                                        <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                    <div class="form-hint">Masukkan harga tanpa titik atau koma</div>
                                </div>
                            </div>

                            <!-- Additional Info Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4><i class="fa fa-info-circle"></i> Informasi Tambahan</h4>
                                    <span class="section-badge optional">Opsional</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="stock" class="form-label-modern">Stock Number</label>
                                            <input type="text" class="form-control-modern @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $car->stock) }}" placeholder="K99D10459934" maxlength="50">
                                            @error('stock')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="vin" class="form-label-modern">VIN Number</label>
                                            <input type="text" class="form-control-modern @error('vin') is-invalid @enderror" id="vin" name="vin" value="{{ old('vin', $car->vin) }}" placeholder="3VWKM245686" maxlength="50">
                                            @error('vin')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="msrp" class="form-label-modern">MSRP (Harga Asli)</label>
                                            <div class="input-group-modern">
                                                <span class="input-prefix">Rp</span>
                                                <input type="text" class="form-control-modern @error('msrp') is-invalid @enderror" id="msrp" name="msrp" value="{{ old('msrp', $car->msrp) }}" placeholder="120000000" maxlength="15">
                                            </div>
                                            @error('msrp')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="dealer_discounts" class="form-label-modern">Dealer Discounts</label>
                                            <div class="input-group-modern">
                                                <span class="input-prefix">Rp</span>
                                                <input type="text" class="form-control-modern @error('dealer_discounts') is-invalid @enderror" id="dealer_discounts" name="dealer_discounts" value="{{ old('dealer_discounts', $car->dealer_discounts) }}" placeholder="3000000" maxlength="15">
                                            </div>
                                            @error('dealer_discounts')
                                                <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group-modern">
                                    <label for="description" class="form-label-modern">Deskripsi / General Information</label>
                                    <textarea class="form-control-modern @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Masukkan deskripsi umum tentang mobil...">{{ old('description', $car->description) }}</textarea>
                                    @error('description')
                                        <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group-modern">
                                    <label for="location" class="form-label-modern">Lokasi Kendaraan</label>
                                    <input type="text" class="form-control-modern @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $car->location) }}" placeholder="Jakarta, Bandung, dll" maxlength="255">
                                    @error('location')
                                        <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Features Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h4><i class="fa fa-star"></i> Features</h4>
                                    <span class="section-badge optional">Opsional</span>
                                </div>

                                <div class="form-group-modern">
                                    <label class="form-label-modern">Interior Features</label>
                                    <div class="features-grid">
                                        @php
                                            $interiorFeatures = old('interior_features', $car->interior_features ?? []);
                                            if (is_string($interiorFeatures)) {
                                                $interiorFeatures = json_decode($interiorFeatures, true) ?? [];
                                            }
                                        @endphp
                                        @for($i = 0; $i < 6; $i++)
                                            <input type="text" class="form-control-modern feature-input" name="interior_features[]" value="{{ $interiorFeatures[$i] ?? '' }}" placeholder="Feature {{ $i + 1 }} (kosongkan jika tidak ada)">
                                        @endfor
                                    </div>
                                </div>

                                <div class="form-group-modern">
                                    <label class="form-label-modern">Safety Features</label>
                                    <div class="features-grid">
                                        @php
                                            $safetyFeatures = old('safety_features', $car->safety_features ?? []);
                                            if (is_string($safetyFeatures)) {
                                                $safetyFeatures = json_decode($safetyFeatures, true) ?? [];
                                            }
                                        @endphp
                                        @for($i = 0; $i < 6; $i++)
                                            <input type="text" class="form-control-modern feature-input" name="safety_features[]" value="{{ $safetyFeatures[$i] ?? '' }}" placeholder="Feature {{ $i + 1 }} (kosongkan jika tidak ada)">
                                        @endfor
                                    </div>
                                </div>

                                <div class="form-group-modern">
                                    <label class="form-label-modern">Extra Features</label>
                                    <div class="features-grid">
                                        @php
                                            $extraFeatures = old('extra_features', $car->extra_features ?? []);
                                            if (is_string($extraFeatures)) {
                                                $extraFeatures = json_decode($extraFeatures, true) ?? [];
                                            }
                                        @endphp
                                        @for($i = 0; $i < 6; $i++)
                                            <input type="text" class="form-control-modern feature-input" name="extra_features[]" value="{{ $extraFeatures[$i] ?? '' }}" placeholder="Feature {{ $i + 1 }} (kosongkan jika tidak ada)">
                                        @endfor
                                    </div>
                                </div>

                                <div class="form-group-modern">
                                    <label for="technical_specs" class="form-label-modern">Technical Specifications</label>
                                    <textarea class="form-control-modern @error('technical_specs') is-invalid @enderror" id="technical_specs" name="technical_specs" rows="5" placeholder="Masukkan spesifikasi teknis mobil...">{{ old('technical_specs', $car->technical_specs) }}</textarea>
                                    @error('technical_specs')
                                        <div class="error-message"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions-modern">
                                <button type="submit" class="btn-submit-modern">
                                    <i class="fa fa-save"></i>
                                    <span>Update Mobil</span>
                                </button>
                                <a href="{{ route('cars.index') }}" class="btn-cancel-modern">
                                    <i class="fa fa-times"></i>
                                    <span>Batal</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Edit Car Section End -->

<style>
    /* Global fix for select dropdowns - CRITICAL */
    body, html {
        overflow-x: hidden;
    }
    
    .container, .row, [class*="col-"] {
        overflow: visible !important;
        position: relative;
    }
    
    /* Force all parent containers to allow dropdown */
    .form-card-modern,
    .form-card-body,
    .form-section,
    .form-group-modern,
    .select-wrapper {
        overflow: visible !important;
        position: relative !important;
    }
    
    /* Hide duplicate select tipe - ensure only one is visible */
    select#tipe:not(:first-of-type),
    select[name="tipe"]:not(:first-of-type) {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        width: 0 !important;
        position: absolute !important;
        left: -9999px !important;
        pointer-events: none !important;
    }
    
    /* Hide duplicate form-group-modern that contains tipe select */
    .form-group-modern:has(select#tipe) ~ .form-group-modern:has(select#tipe) {
        display: none !important;
    }
    
    /* Prevent nice-select from styling tipe select */
    select#tipe.nice-select,
    .nice-select:has(select#tipe) {
        display: none !important;
    }
    
    select#tipe {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Modern Edit Car Form Styles */
    .edit-car-section {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        min-height: 100vh;
    }

    /* Page Header */
    .page-header-modern {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .header-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #df2d24, #ff6b6b);
        border-radius: 12px;
        color: #fff;
        font-size: 24px;
    }

    .header-content {
        flex: 1;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0 0 5px 0;
        background: linear-gradient(135deg, #1a1a1a, #4a4a4a);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-subtitle {
        color: #6b7280;
        margin: 0;
        font-size: 14px;
    }

    .btn-back {
        padding: 12px 24px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        color: #1a1a1a;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        border: 1px solid #e0e0e0;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #e9ecef, #dee2e6);
        transform: translateX(-4px);
        color: #1a1a1a;
        text-decoration: none;
    }

    /* Form Card */
    .form-card-modern {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        overflow: visible !important;
        border: 1px solid rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        position: relative;
        z-index: 1;
    }

    .form-card-header {
        background: linear-gradient(135deg, #df2d24, #ff6b6b);
        padding: 24px 30px;
        color: #fff;
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card-body {
        padding: 30px;
        overflow: visible !important;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        position: relative;
        z-index: 1;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 30px;
        padding: 24px;
        background: linear-gradient(135deg, #fafbfc, #ffffff);
        border-radius: 16px;
        border: 1px solid #f0f0f0;
        transition: all 0.3s;
        overflow: visible !important;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        position: relative;
    }
    
    /* Special handling for sections with selects */
    .form-section:has(select) {
        overflow: visible !important;
        z-index: 1;
    }

    .form-section * {
        max-width: 100%;
        box-sizing: border-box;
    }

    .form-section:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-color: #e0e0e0;
    }

    .form-section .row {
        margin-left: -15px;
        margin-right: -15px;
    }

    .form-section .row > [class*="col-"] {
        padding-left: 15px;
        padding-right: 15px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-header h4 i {
        color: #df2d24;
    }

    .section-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-badge.optional {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        color: #6b7280;
    }

    /* Form Groups */
    .form-group-modern {
        margin-bottom: 16px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        position: relative;
        overflow: visible;
    }
    
    .select-wrapper {
        position: relative !important;
        z-index: 9999 !important;
        overflow: visible !important;
    }
    
    .select-wrapper select {
        position: relative !important;
        z-index: 99999 !important;
        pointer-events: auto !important;
        -webkit-appearance: menulist !important;
        -moz-appearance: menulist !important;
        appearance: menulist !important;
        cursor: pointer !important;
    }
    
    .select-wrapper select:focus,
    .select-wrapper select:active,
    .select-wrapper select:hover {
        z-index: 999999 !important;
        position: relative !important;
    }
    
    /* Remove any pseudo-elements that might block */
    .select-wrapper::before,
    .select-wrapper::after {
        display: none !important;
    }

    .form-group-modern .form-label-modern {
        margin-bottom: 4px;
        margin-top: 0;
    }

    .form-group-modern * {
        max-width: 100%;
        box-sizing: border-box;
    }

    .form-label-modern {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
        margin-top: 0;
        line-height: 1.4;
    }

    .required {
        color: #df2d24;
    }

    .form-control-modern {
        width: 100%;
        max-width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s;
        background: #fff;
        line-height: 1.5;
        height: auto;
        min-height: 46px;
        display: block;
        box-sizing: border-box;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0;
    }
    
    /* Exception for select - must have overflow visible */
    select.form-control-modern {
        overflow: visible !important;
    }

    input.form-control-modern,
    textarea.form-control-modern {
        vertical-align: middle;
    }


    .form-control-modern:focus {
        outline: none;
        border-color: #df2d24;
        box-shadow: 0 0 0 4px rgba(223, 45, 36, 0.1);
    }

    .form-control-modern.is-invalid {
        border-color: #dc2626;
    }

    .input-group-modern {
        display: flex;
        align-items: center;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .input-prefix {
        padding: 14px 18px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        color: #6b7280;
        font-weight: 700;
        border-right: 2px solid #e0e0e0;
    }

    .input-group-modern .form-control-modern {
        border: none;
        flex: 1;
        display: flex;
        align-items: center;
    }

    select.form-control-modern {
        /* Use native browser dropdown - no custom styling that might break it */
        -webkit-appearance: menulist !important;
        -moz-appearance: menulist !important;
        appearance: menulist !important;
        cursor: pointer !important;
        display: block !important;
        line-height: 1.5;
        padding: 12px 16px !important;
        padding-right: 40px !important;
        height: 46px !important;
        box-sizing: border-box !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow: visible !important;
        margin: 0 !important;
        position: relative !important;
        z-index: 999999 !important;
        vertical-align: middle;
        pointer-events: auto !important;
        -webkit-user-select: auto !important;
        -moz-user-select: auto !important;
        user-select: auto !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 12px !important;
        font-size: 15px !important;
        background: #fff !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    select.form-control-modern:focus,
    select.form-control-modern:active,
    select.form-control-modern:hover {
        z-index: 9999999 !important;
        outline: none !important;
        border-color: #df2d24 !important;
        box-shadow: 0 0 0 4px rgba(223, 45, 36, 0.1) !important;
    }
    
    /* Ensure dropdown options are visible - browser native dropdown */
    select.form-control-modern option {
        background: #fff !important;
        color: #1a1a1a !important;
        padding: 12px !important;
        line-height: 1.5 !important;
        display: block !important;
    }

    .input-group-modern:focus-within {
        border-color: #df2d24;
        box-shadow: 0 0 0 4px rgba(223, 45, 36, 0.1);
    }

    .form-hint {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 6px;
    }

    .error-message {
        color: #dc2626;
        font-size: 13px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Image Section */
    .existing-images-section {
        margin-bottom: 30px;
    }

    .section-label {
        font-size: 14px;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .image-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        width: 100%;
        overflow: visible;
    }

    .image-item-modern {
        position: relative;
    }

    .image-wrapper-modern {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e0e0e0;
        transition: all 0.3s;
        background: #f5f5f5;
        width: 100%;
        height: 180px;
    }

    .image-wrapper-modern:hover {
        border-color: #df2d24;
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(223, 45, 36, 0.2);
    }

    .image-preview-modern {
        width: 100%;
        height: 180px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .image-preview-modern:hover {
        transform: scale(1.1);
    }

    .image-placeholder {
        width: 100%;
        height: 180px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }

    .image-placeholder i {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .btn-remove-image {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        transition: all 0.3s;
        z-index: 2;
    }

    .btn-remove-image:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.5);
    }

    .image-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.75);
        color: #fff;
        padding: 6px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
    }

    .upload-section-modern {
        margin-top: 20px;
    }

    .upload-label-modern {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 24px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: 2px dashed #d0d0d0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .upload-label-modern:hover {
        background: linear-gradient(135deg, #e9ecef, #dee2e6);
        border-color: #df2d24;
        color: #df2d24;
    }

    .file-input-modern {
        display: none;
    }

    .upload-hint {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Form Actions */
    .form-actions-modern {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-submit-modern,
    .btn-cancel-modern {
        flex: 1;
        padding: 16px 24px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-submit-modern {
        background: linear-gradient(135deg, #df2d24, #ff6b6b);
        color: #fff;
        box-shadow: 0 4px 15px rgba(223, 45, 36, 0.3);
    }

    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(223, 45, 36, 0.4);
        background: linear-gradient(135deg, #ff6b6b, #ff5252);
    }

    .btn-cancel-modern {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        color: #1a1a1a;
        border: 2px solid #e0e0e0;
    }

    .btn-cancel-modern:hover {
        background: linear-gradient(135deg, #e9ecef, #dee2e6);
        transform: translateY(-2px);
        color: #1a1a1a;
        text-decoration: none;
    }

    /* Features Grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .feature-input {
        margin-bottom: 0;
    }

    /* Textarea */
    textarea.form-control-modern {
        resize: vertical;
        min-height: 120px;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-section {
        animation: fadeIn 0.5s ease-out;
    }

    .form-section:nth-child(1) { animation-delay: 0.1s; }
    .form-section:nth-child(2) { animation-delay: 0.2s; }
    .form-section:nth-child(3) { animation-delay: 0.3s; }
    .form-section:nth-child(4) { animation-delay: 0.4s; }
    .form-section:nth-child(5) { animation-delay: 0.5s; }

    /* Responsive */
    @media (max-width: 991px) {
        .form-card-body {
            padding: 30px 20px;
        }

        .form-section {
            padding: 20px;
        }

        .page-header-modern {
            flex-direction: column;
            text-align: center;
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

        .page-title {
            font-size: 24px;
        }
    }
</style>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Remove existing image
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-existing-image')) {
            const button = e.target.closest('.remove-existing-image');
            const imageItem = button.closest('.existing-image-item');
            const existingCount = document.querySelectorAll('.existing-image-item:not([style*="display: none"])').length;
            const newFiles = document.getElementById('images').files.length;
            
            if (existingCount + newFiles <= 1) {
                alert('Minimal 1 gambar diperlukan.');
                return;
            }
            
            imageItem.style.display = 'none';
            imageItem.querySelector('.existing-image-input').remove();
        }
    });
    
    // Preview new images before upload
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        const files = e.target.files;
        const existingCount = document.querySelectorAll('.existing-image-item:not([style*="display: none"])').length;
        const maxFiles = 6;
        const maxNewFiles = maxFiles - existingCount;
        
        if (files.length > maxNewFiles) {
            alert('Total gambar tidak boleh lebih dari ' + maxFiles + '. Anda sudah memiliki ' + existingCount + ' gambar.');
            e.target.value = '';
            return;
        }
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const item = document.createElement('div');
                    item.className = 'image-item-modern';
                    item.innerHTML = `
                        <div class="image-wrapper-modern">
                            <img src="${e.target.result}" alt="Preview ${i + 1}" class="image-preview-modern">
                            <div class="image-label" style="background: linear-gradient(135deg, #10b981, #34d399);">
                                <i class="fa fa-plus-circle"></i> Baru
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
    
    // Prevent nice-select from initializing on tipe select
    (function() {
        // Override nice-select initialization for tipe select
        if (typeof $ !== 'undefined' && typeof $.fn.niceSelect !== 'undefined') {
            const originalNiceSelect = $.fn.niceSelect;
            $.fn.niceSelect = function(options) {
                // Don't initialize nice-select on select#tipe
                if (this.attr('id') === 'tipe' || this.attr('name') === 'tipe') {
                    return this;
                }
                return originalNiceSelect.call(this, options);
            };
        }
    })();

    // Ensure only one select dropdown exists for Tipe
    function removeDuplicateSelects() {
        // Find all selects with id="tipe"
        const tipeSelects = document.querySelectorAll('select#tipe');
        
        // Find the original select (one with label)
        let originalSelect = null;
        const formGroups = document.querySelectorAll('.form-group-modern');
        formGroups.forEach(function(group) {
            const label = group.querySelector('label[for="tipe"]');
            if (label) {
                const select = group.querySelector('select#tipe');
                if (select) {
                    originalSelect = select;
                }
            }
        });
        
        if (tipeSelects.length > 1) {
            // Keep only the original one, remove all others
            tipeSelects.forEach(function(select) {
                if (select !== originalSelect) {
                    // Remove nice-select wrapper if exists
                    const niceSelectWrapper = select.closest('.nice-select');
                    if (niceSelectWrapper) {
                        niceSelectWrapper.remove();
                    } else {
                        // Remove parent form-group if it doesn't have label
                        const parent = select.parentElement;
                        if (parent) {
                            const hasLabel = parent.querySelector('label[for="tipe"]');
                            if (!hasLabel) {
                                parent.remove();
                            } else {
                                select.remove();
                            }
                        } else {
                            select.remove();
                        }
                    }
                }
            });
        }
        
        // Remove all nice-select wrappers for tipe select
        const niceSelects = document.querySelectorAll('.nice-select');
        niceSelects.forEach(function(niceSelect) {
            const select = niceSelect.querySelector('select#tipe');
            if (select) {
                // Remove nice-select wrapper and restore original select
                if (niceSelect.parentNode) {
                    niceSelect.parentNode.replaceChild(select, niceSelect);
                }
            }
        });
        
        // Ensure only one select is visible
        const remainingSelects = document.querySelectorAll('select#tipe');
        if (remainingSelects.length > 1) {
            for (let i = 1; i < remainingSelects.length; i++) {
                remainingSelects[i].remove();
            }
        }
    }

    // Run removeDuplicateSelects function multiple times to catch all duplicates
    function initRemoveDuplicates() {
        removeDuplicateSelects();
        
        // Run again after delays to catch nice-select modifications
        setTimeout(removeDuplicateSelects, 50);
        setTimeout(removeDuplicateSelects, 100);
        setTimeout(removeDuplicateSelects, 200);
        setTimeout(removeDuplicateSelects, 300);
        setTimeout(removeDuplicateSelects, 500);
        setTimeout(removeDuplicateSelects, 1000);
        setTimeout(removeDuplicateSelects, 2000);
    }

    // Use MutationObserver to watch for DOM changes
    const observer = new MutationObserver(function(mutations) {
        let shouldCheck = false;
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        if (node.id === 'tipe' || 
                            node.querySelector && node.querySelector('select#tipe') ||
                            node.classList && node.classList.contains('nice-select')) {
                            shouldCheck = true;
                        }
                    }
                });
            }
        });
        if (shouldCheck) {
            setTimeout(removeDuplicateSelects, 10);
        }
    });

    // Run when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initRemoveDuplicates();
            // Start observing
            if (document.body) {
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        });
    } else {
        initRemoveDuplicates();
        // Start observing
        if (document.body) {
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    // Also run after window load to catch any late-loading scripts
    window.addEventListener('load', function() {
        setTimeout(removeDuplicateSelects, 100);
        setTimeout(removeDuplicateSelects, 300);
        setTimeout(removeDuplicateSelects, 500);
    });
</script>
@endsection

