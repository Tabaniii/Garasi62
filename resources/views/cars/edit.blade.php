@extends('template.temp')

@section('content')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Edit Car Section Begin -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-danger mb-2">Edit Mobil</h2>
                <p class="text-muted mb-0">Ubah informasi mobil di bawah ini</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select class="form-control @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="rent" {{ old('tipe', $car->tipe) == 'rent' ? 'selected' : '' }}>Rent (Sewa)</option>
                                    <option value="buy" {{ old('tipe', $car->tipe) == 'buy' ? 'selected' : '' }}>Buy (Beli)</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gambar Mobil (Maksimal 6 gambar)</label>
                                
                                @if($car->image && is_array($car->image) && count($car->image) > 0)
                                <div class="mb-3">
                                    <label class="form-label small text-muted d-block mb-2">
                                        <i class="fas fa-images me-2"></i>Gambar yang ada ({{ count($car->image) }} gambar):
                                    </label>
                                    <div id="existingImages" class="row g-3 mb-3">
                                        @foreach($car->image as $index => $imagePath)
                                        <div class="col-md-3 col-sm-4 col-6 existing-image-item" data-image="{{ $imagePath }}">
                                            <div class="position-relative existing-image-wrapper">
                                                @if(file_exists(public_path('storage/' . $imagePath)))
                                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Gambar {{ $index + 1 }}" class="img-thumbnail w-100 existing-img" style="height: 180px; object-fit: cover; border-radius: 8px; cursor: pointer;" onclick="window.open('{{ asset('storage/' . $imagePath) }}', '_blank')">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px; border-radius: 8px; border: 2px dashed #ddd;">
                                                        <div class="text-center text-muted">
                                                            <i class="fas fa-image fa-2x mb-2"></i>
                                                            <p class="small mb-0">Gambar tidak ditemukan</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-existing-image" data-image="{{ $imagePath }}" style="border-radius: 50%; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white text-center py-1" style="border-radius: 0 0 8px 8px; font-size: 11px;">
                                                    Gambar {{ $index + 1 }}
                                                </div>
                                            </div>
                                            <input type="hidden" name="existing_images[]" value="{{ $imagePath }}" class="existing-image-input">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <label for="images" class="form-label d-block mb-2">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Gambar Baru (Opsional):
                                </label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih gambar baru untuk ditambahkan (Format: JPG, PNG, GIF, WEBP, Maks 5MB per gambar). Total gambar tidak boleh lebih dari 6.
                                </small>
                                <div id="imagePreview" class="mt-3 row g-3"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="brand" class="form-label">Brand/Merek <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $car->brand) }}" placeholder="Contoh: Toyota, Honda" required maxlength="20">
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="{{ old('tahun', $car->tahun) }}" placeholder="2020" required maxlength="4">
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kilometer" class="form-label">Kilometer <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kilometer') is-invalid @enderror" id="kilometer" name="kilometer" value="{{ old('kilometer', $car->kilometer) }}" placeholder="50000" required maxlength="6">
                                    @error('kilometer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="transmisi" class="form-label">Transmisi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('transmisi') is-invalid @enderror" id="transmisi" name="transmisi" required>
                                        <option value="">Pilih Transmisi</option>
                                        <option value="Manual" {{ old('transmisi', $car->transmisi) == 'Manual' ? 'selected' : '' }}>Manual</option>
                                        <option value="Automatic" {{ old('transmisi', $car->transmisi) == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                        <option value="CVT" {{ old('transmisi', $car->transmisi) == 'CVT' ? 'selected' : '' }}>CVT</option>
                                    </select>
                                    @error('transmisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $car->harga) }}" placeholder="250000000" required maxlength="10">
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Masukkan harga tanpa titik atau koma</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="metode" class="form-label">Metode <span class="text-danger">*</span></label>
                                    <select class="form-control @error('metode') is-invalid @enderror" id="metode" name="metode" required>
                                        <option value="">Pilih Metode</option>
                                        <option value="Cash" {{ old('metode', $car->metode) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Kredit" {{ old('metode', $car->metode) == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                                    </select>
                                    @error('metode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="kapasitasmesin" class="form-label">Kapasitas Mesin <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kapasitasmesin') is-invalid @enderror" id="kapasitasmesin" name="kapasitasmesin" value="{{ old('kapasitasmesin', $car->kapasitasmesin) }}" placeholder="Contoh: 1500cc, 2000cc" required maxlength="50">
                                @error('kapasitasmesin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3">Informasi Detail (Opsional)</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stock Number</label>
                                    <input type="text" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $car->stock) }}" placeholder="K99D10459934" maxlength="50">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="vin" class="form-label">VIN Number</label>
                                    <input type="text" class="form-control @error('vin') is-invalid @enderror" id="vin" name="vin" value="{{ old('vin', $car->vin) }}" placeholder="3VWKM245686" maxlength="50">
                                    @error('vin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="msrp" class="form-label">MSRP (Harga Asli)</label>
                                    <input type="text" class="form-control @error('msrp') is-invalid @enderror" id="msrp" name="msrp" value="{{ old('msrp', $car->msrp) }}" placeholder="120000000" maxlength="15">
                                    @error('msrp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="dealer_discounts" class="form-label">Dealer Discounts</label>
                                    <input type="text" class="form-control @error('dealer_discounts') is-invalid @enderror" id="dealer_discounts" name="dealer_discounts" value="{{ old('dealer_discounts', $car->dealer_discounts) }}" placeholder="3000000" maxlength="15">
                                    @error('dealer_discounts')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi / General Information</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Masukkan deskripsi umum tentang mobil...">{{ old('description', $car->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Interior Features</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        @php
                                            $interiorFeatures = old('interior_features', $car->interior_features ?? []);
                                            if (is_string($interiorFeatures)) {
                                                $interiorFeatures = json_decode($interiorFeatures, true) ?? [];
                                            }
                                        @endphp
                                        <input type="text" class="form-control mb-2" name="interior_features[]" value="{{ $interiorFeatures[0] ?? '' }}" placeholder="Auxiliary heating">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" value="{{ $interiorFeatures[1] ?? '' }}" placeholder="Bluetooth">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" value="{{ $interiorFeatures[2] ?? '' }}" placeholder="CD player">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" value="{{ $interiorFeatures[3] ?? '' }}" placeholder="Central locking">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" value="{{ $interiorFeatures[4] ?? '' }}" placeholder="Feature lainnya...">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" value="{{ $interiorFeatures[5] ?? '' }}" placeholder="Feature lainnya...">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ada</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Safety Features</label>
                                <div class="row">
                                    @php
                                        $safetyFeatures = old('safety_features', $car->safety_features ?? []);
                                        if (is_string($safetyFeatures)) {
                                            $safetyFeatures = json_decode($safetyFeatures, true) ?? [];
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" value="{{ $safetyFeatures[0] ?? '' }}" placeholder="Head-up display">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" value="{{ $safetyFeatures[1] ?? '' }}" placeholder="MP3 interface">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" value="{{ $safetyFeatures[2] ?? '' }}" placeholder="Navigation system">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" value="{{ $safetyFeatures[3] ?? '' }}" placeholder="Panoramic roof">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" value="{{ $safetyFeatures[4] ?? '' }}" placeholder="Feature lainnya...">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" value="{{ $safetyFeatures[5] ?? '' }}" placeholder="Feature lainnya...">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ada</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Extra Features</label>
                                <div class="row">
                                    @php
                                        $extraFeatures = old('extra_features', $car->extra_features ?? []);
                                        if (is_string($extraFeatures)) {
                                            $extraFeatures = json_decode($extraFeatures, true) ?? [];
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" value="{{ $extraFeatures[0] ?? '' }}" placeholder="Alloy wheels">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" value="{{ $extraFeatures[1] ?? '' }}" placeholder="Electric side mirror">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" value="{{ $extraFeatures[2] ?? '' }}" placeholder="Sports package">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" value="{{ $extraFeatures[3] ?? '' }}" placeholder="Sports suspension">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" value="{{ $extraFeatures[4] ?? '' }}" placeholder="Parking sensors">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" value="{{ $extraFeatures[5] ?? '' }}" placeholder="Feature lainnya...">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ada</small>
                            </div>

                            <div class="mb-3">
                                <label for="technical_specs" class="form-label">Technical Specifications</label>
                                <textarea class="form-control @error('technical_specs') is-invalid @enderror" id="technical_specs" name="technical_specs" rows="4" placeholder="Masukkan spesifikasi teknis mobil...">{{ old('technical_specs', $car->technical_specs) }}</textarea>
                                @error('technical_specs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi Kendaraan</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $car->location) }}" placeholder="Jakarta, Bandung, dll" maxlength="255">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-danger flex-fill">
                                    <i class="fa fa-save me-2"></i>Update Mobil
                                </button>
                                <a href="{{ route('cars.index') }}" class="btn btn-secondary flex-fill">
                                    <i class="fa fa-times me-2"></i>Batal
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
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 col-6';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" alt="Preview ${i + 1}" class="img-thumbnail w-100" style="height: 180px; object-fit: cover; border-radius: 8px;">
                            <div class="position-absolute top-0 start-0 m-2 bg-success text-white px-2 py-1 rounded" style="font-size: 11px; font-weight: 600;">
                                <i class="fas fa-plus-circle me-1"></i>Baru
                            </div>
                        </div>
                    `;
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        }
    });
</script>
@endsection

