@extends('layouts.admin')

@section('header-title', 'Tambah Testimoni')

@section('content')
<div class="form-section testimonial-form-section">
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 5px; border-left: 4px solid #dc2626;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2" style="font-size: 20px;"></i>
            <div class="flex-grow-1">
                <strong>Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="form-header-section">
        <div class="form-header-icon">
            <i class="fas fa-quote-right"></i>
        </div>
        <div>
            <h3 class="form-title">Tambah Testimoni Baru</h3>
            <p class="form-subtitle">Isi form di bawah untuk menambahkan testimoni baru yang akan ditampilkan di halaman About.</p>
        </div>
    </div>

    <form action="{{ route('testimonials.admin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-card">
            <div class="form-card-header">
                <h5 class="form-card-title">
                    <i class="fas fa-user me-2"></i>Informasi Pribadi
                </h5>
            </div>
            <div class="form-card-body">
                <div class="mb-4">
                    <label for="name" class="form-label-custom">
                        <i class="fas fa-user-circle me-2"></i>Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control-custom @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <div class="invalid-feedback-custom">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <h5 class="form-card-title">
                    <i class="fas fa-briefcase me-2"></i>Informasi Profesional
                </h5>
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="position" class="form-label-custom">
                            <i class="fas fa-user-tie me-2"></i>Posisi/Jabatan
                        </label>
                        <input type="text" class="form-control-custom @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" placeholder="Contoh: CEO, Marketing Manager">
                        @error('position')
                            <div class="invalid-feedback-custom">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="company" class="form-label-custom">
                            <i class="fas fa-building me-2"></i>Perusahaan
                        </label>
                        <input type="text" class="form-control-custom @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company') }}" placeholder="Contoh: Garasi62">
                        @error('company')
                            <div class="invalid-feedback-custom">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <h5 class="form-card-title">
                    <i class="fas fa-star me-2"></i>Rating & Testimoni
                </h5>
            </div>
            <div class="form-card-body">
                <div class="mb-4">
                    <label for="rating" class="form-label-custom">
                        <i class="fas fa-star me-2"></i>Rating <span class="text-danger">*</span>
                    </label>
                    <div class="rating-select-wrapper">
                        <select class="form-select-custom @error('rating') is-invalid @enderror" id="rating" name="rating" required>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>
                                    @for($j = 0; $j < $i; $j++)⭐@endfor {{ $i }} Bintang
                                </option>
                            @endfor
                        </select>
                        <div class="rating-preview" id="ratingPreview">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star {{ $i < (old('rating', 5)) ? 'active' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @error('rating')
                        <div class="invalid-feedback-custom">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label-custom">
                        <i class="fas fa-comment-dots me-2"></i>Pesan Testimoni <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control-custom @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Tuliskan testimoni dari pelanggan..." required>{{ old('message') }}</textarea>
                    <small class="form-text-custom">
                        <i class="fas fa-info-circle me-1"></i>Pesan akan ditampilkan di halaman About dengan format kutipan.
                    </small>
                    @error('message')
                        <div class="invalid-feedback-custom">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <h5 class="form-card-title">
                    <i class="fas fa-image me-2"></i>Foto & Pengaturan
                </h5>
            </div>
            <div class="form-card-body">
                <div class="mb-4">
                    <label for="image" class="form-label-custom">
                        <i class="fas fa-camera me-2"></i>Foto Profil
                    </label>
                    <div class="file-upload-wrapper">
                        <input type="file" class="file-input-custom @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                        <label for="image" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt me-2"></i>
                            <span>Pilih Foto</span>
                        </label>
                        <small class="form-text-custom d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>Format: JPG, PNG, GIF, WEBP (Maks 5MB)
                        </small>
                    </div>
                    @error('image')
                        <div class="invalid-feedback-custom d-block">{{ $message }}</div>
                    @enderror
                    <div id="imagePreview" class="image-preview-container mt-3"></div>
                </div>

            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit-custom">
                <i class="fas fa-save me-2"></i>Simpan Testimoni
            </button>
            <a href="{{ route('testimonials.admin.index') }}" class="btn-cancel-custom">
                <i class="fas fa-times me-2"></i>Batal
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const maxSize = 5 * 1024 * 1024;
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.');
            input.value = '';
            preview.innerHTML = '';
            return;
        }
        
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            input.value = '';
            preview.innerHTML = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="preview-image-wrapper">
                    <img src="${e.target.result}" class="preview-image" alt="Preview">
                    <button type="button" class="preview-remove" onclick="removePreview()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <small class="d-block text-muted mt-2">
                    <i class="fas fa-info-circle me-1"></i>Ukuran: ${(file.size / 1024 / 1024).toFixed(2)} MB
                </small>
            `;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
}

function removePreview() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').innerHTML = '';
}

// Rating preview
document.getElementById('rating').addEventListener('change', function() {
    const rating = parseInt(this.value);
    const preview = document.getElementById('ratingPreview');
    preview.innerHTML = '';
    for(let i = 0; i < 5; i++) {
        const star = document.createElement('i');
        star.className = 'fas fa-star' + (i < rating ? ' active' : '');
        preview.appendChild(star);
    }
});
</script>

<style>
.testimonial-form-section {
    max-width: 900px;
    margin: 0 auto;
}

.form-header-section {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 32px;
    padding: 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 5px;
    border-left: 4px solid #dc2626;
}

.form-header-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
}

.form-title {
    font-size: 28px;
    font-weight: 800;
    color: #1a1a1a;
    margin: 0 0 8px 0;
}

.form-subtitle {
    font-size: 15px;
    color: #6b7280;
    margin: 0;
}

.form-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    margin-bottom: 24px;
    overflow: hidden;
    transition: all 0.3s;
}

.form-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    border-color: #dc2626;
}

.form-card-header {
    padding: 20px 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-bottom: 1px solid #f0f0f0;
}

.form-card-title {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
}

.form-card-title i {
    color: #dc2626;
}

.form-card-body {
    padding: 24px;
}

.form-label-custom {
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 10px;
    display: block;
    font-size: 14px;
}

.form-label-custom i {
    color: #dc2626;
}

.form-control-custom {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 5px;
    font-size: 15px;
    transition: all 0.3s;
    background: #ffffff;
}

.form-control-custom:focus {
    outline: none;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-control-custom.is-invalid {
    border-color: #dc2626;
}

.invalid-feedback-custom {
    display: block;
    width: 100%;
    margin-top: 8px;
    font-size: 13px;
    color: #dc2626;
}

.form-text-custom {
    display: block;
    margin-top: 8px;
    font-size: 13px;
    color: #6b7280;
}

.rating-select-wrapper {
    position: relative;
}

.form-select-custom {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 5px;
    font-size: 15px;
    transition: all 0.3s;
    background: #ffffff;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 40px;
}

.form-select-custom:focus {
    outline: none;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.rating-preview {
    margin-top: 12px;
    display: flex;
    gap: 4px;
}

.rating-preview i {
    font-size: 20px;
    color: #e5e7eb;
    transition: color 0.3s;
}

.rating-preview i.active {
    color: #fbbf24;
}

.file-upload-wrapper {
    position: relative;
}

.file-input-custom {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.file-upload-label {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px dashed #dc2626;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 600;
    color: #dc2626;
}

.file-upload-label:hover {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    border-color: #dc2626;
}

.image-preview-container {
    position: relative;
}

.preview-image-wrapper {
    position: relative;
    display: inline-block;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.preview-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    display: block;
}

.preview-remove {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 32px;
    height: 32px;
    background: rgba(220, 38, 38, 0.9);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.preview-remove:hover {
    background: #dc2626;
    transform: scale(1.1);
}

.form-switch-custom {
    padding: 16px;
    background: #f8f9fa;
    border-radius: 5px;
}

.switch-wrapper {
    display: flex;
    align-items: center;
}

.switch-input {
    display: none;
}

.switch-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    gap: 12px;
}

.switch-slider {
    position: relative;
    width: 50px;
    height: 26px;
    background: #e5e7eb;
    border-radius: 5px;
    transition: all 0.3s;
}

.switch-slider::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: white;
    top: 3px;
    left: 3px;
    transition: all 0.3s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.switch-input:checked + .switch-label .switch-slider {
    background: linear-gradient(135deg, #dc2626, #991b1b);
}

.switch-input:checked + .switch-label .switch-slider::before {
    transform: translateX(24px);
}

.switch-text {
    font-weight: 600;
    color: #1a1a1a;
    font-size: 15px;
}

.switch-text i {
    color: #dc2626;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 2px solid #f0f0f0;
}

.btn-submit-custom {
    flex: 1;
    padding: 14px 28px;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
}

.btn-submit-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
    color: white;
}

.btn-cancel-custom {
    flex: 1;
    padding: 14px 28px;
    background: #ffffff;
    color: #6b7280;
    border: 2px solid #e9ecef;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-cancel-custom:hover {
    background: #f8f9fa;
    border-color: #dc2626;
    color: #dc2626;
}

@media (max-width: 768px) {
    .form-header-section {
        flex-direction: column;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-submit-custom,
    .btn-cancel-custom {
        width: 100%;
    }
}
</style>
@endsection

