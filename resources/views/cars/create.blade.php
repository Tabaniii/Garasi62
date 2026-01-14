@extends('template.temp')

@section('content')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Create Car Section Begin -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-danger mb-2">Tambah Mobil Baru</h2>
                <p class="text-muted mb-0">Isi form di bawah ini untuk menambahkan mobil baru</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select class="form-control @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="rent" {{ old('tipe') == 'rent' ? 'selected' : '' }}>Rent (Sewa)</option>
                                    <option value="buy" {{ old('tipe') == 'buy' ? 'selected' : '' }}>Buy (Beli)</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="images" class="form-label">Gambar Mobil <span class="text-danger">*</span> (Maksimal 6 gambar)</label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror @error('error') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple required>
                                @error('images')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('error')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Pilih 1-6 gambar mobil (Format: JPG, PNG, GIF, WEBP, Maks 5MB per gambar)</small>
                                <div id="imagePreview" class="mt-3 row g-2"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="brand" class="form-label">Brand/Merek <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand') }}" placeholder="Contoh: Toyota, Honda" required maxlength="20">
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label">Nama Mobil <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Avanza, Civic, Camry" required maxlength="100">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="{{ old('tahun') }}" placeholder="2020" required maxlength="4">
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kilometer" class="form-label">Kilometer <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kilometer') is-invalid @enderror" id="kilometer" name="kilometer" value="{{ old('kilometer') }}" placeholder="50000" required maxlength="6">
                                    @error('kilometer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="transmisi" class="form-label">Transmisi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('transmisi') is-invalid @enderror" id="transmisi" name="transmisi" required>
                                        <option value="">Pilih Transmisi</option>
                                        <option value="Manual" {{ old('transmisi') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                        <option value="Automatic" {{ old('transmisi') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                        <option value="CVT" {{ old('transmisi') == 'CVT' ? 'selected' : '' }}>CVT</option>
                                    </select>
                                    @error('transmisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga') }}" placeholder="250000000" required maxlength="10">
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Masukkan harga tanpa titik atau koma</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="metode" class="form-label">Metode <span class="text-danger">*</span></label>
                                    <select class="form-control @error('metode') is-invalid @enderror" id="metode" name="metode" required>
                                        <option value="">Pilih Metode</option>
                                        <option value="Cash" {{ old('metode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Kredit" {{ old('metode') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                                    </select>
                                    @error('metode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="kapasitasmesin" class="form-label">Kapasitas Mesin <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kapasitasmesin') is-invalid @enderror" id="kapasitasmesin" name="kapasitasmesin" value="{{ old('kapasitasmesin') }}" placeholder="Contoh: 1500cc, 2000cc" required maxlength="50">
                                @error('kapasitasmesin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3">Informasi Detail (Opsional)</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stock Number</label>
                                    <input type="text" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" placeholder="K99D10459934" maxlength="50">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="vin" class="form-label">VIN Number</label>
                                    <input type="text" class="form-control @error('vin') is-invalid @enderror" id="vin" name="vin" value="{{ old('vin') }}" placeholder="3VWKM245686" maxlength="50">
                                    @error('vin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="msrp" class="form-label">MSRP (Harga Asli)</label>
                                    <input type="text" class="form-control @error('msrp') is-invalid @enderror" id="msrp" name="msrp" value="{{ old('msrp') }}" placeholder="120000000" maxlength="15">
                                    @error('msrp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="dealer_discounts" class="form-label">Dealer Discounts</label>
                                    <input type="text" class="form-control @error('dealer_discounts') is-invalid @enderror" id="dealer_discounts" name="dealer_discounts" value="{{ old('dealer_discounts') }}" placeholder="3000000" maxlength="15">
                                    @error('dealer_discounts')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi / General Information</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Masukkan deskripsi umum tentang mobil...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Interior Features</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" placeholder="Auxiliary heating">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" placeholder="Bluetooth">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" placeholder="CD player">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" placeholder="Central locking">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" placeholder="Feature lainnya...">
                                        <input type="text" class="form-control mb-2" name="interior_features[]" placeholder="Feature lainnya...">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ada</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Safety Features</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" placeholder="Head-up display">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" placeholder="MP3 interface">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" placeholder="Navigation system">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" placeholder="Panoramic roof">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" placeholder="Feature lainnya...">
                                        <input type="text" class="form-control mb-2" name="safety_features[]" placeholder="Feature lainnya...">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ada</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Extra Features</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" placeholder="Alloy wheels">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" placeholder="Electric side mirror">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" placeholder="Sports package">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" placeholder="Sports suspension">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" placeholder="Parking sensors">
                                        <input type="text" class="form-control mb-2" name="extra_features[]" placeholder="Feature lainnya...">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ada</small>
                            </div>

                            <div class="mb-3">
                                <label for="technical_specs" class="form-label">Technical Specifications</label>
                                <textarea class="form-control @error('technical_specs') is-invalid @enderror" id="technical_specs" name="technical_specs" rows="4" placeholder="Masukkan spesifikasi teknis mobil...">{{ old('technical_specs') }}</textarea>
                                @error('technical_specs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi Kendaraan</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="Jakarta, Bandung, dll" maxlength="255">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-danger flex-fill">
                                    <i class="fa fa-save me-2"></i>Simpan Mobil
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
<!-- Create Car Section End -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Preview images before upload
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        const files = e.target.files;
        const maxFiles = 6;
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        if (files.length > maxFiles) {
            alert('Maksimal ' + maxFiles + ' gambar yang diizinkan.');
            e.target.value = '';
            return;
        }
        
        if (files.length < 1) {
            alert('Minimal 1 gambar diperlukan.');
            return;
        }
        
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
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-2';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" alt="Preview ${i + 1}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                            <small class="d-block text-center mt-1 text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
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

