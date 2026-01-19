@extends('layouts.admin')

@section('header-title', 'Tambah Pengguna Baru')

@section('content')
<div class="page-header-section mb-5">
    <div class="page-header-content">
        <div class="page-header-text">
            <div class="page-title-wrapper">
                <h1 class="page-title">Tambah Pengguna Baru</h1>
            </div>
            <p class="page-subtitle">
                <i class="fas fa-info-circle me-2"></i>Isi form di bawah ini untuk menambahkan pengguna baru
            </p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('users.index') }}" class="btn-back-dashboard">
                <i class="fas fa-arrow-left me-2"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8 mx-auto">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-user-plus me-2"></i>Form Tambah Pengguna
                </h5>
            </div>
            <form action="{{ route('users.store') }}" method="POST" id="userForm">
                @csrf
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" required maxlength="15">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                               id="city" name="city" value="{{ old('city') }}" required maxlength="255">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="institution" class="form-label">Institusi <span class="text-danger">*</span></label>
                        <select class="form-control @error('institution') is-invalid @enderror" id="institution" name="institution" required>
                            <option value="">Pilih Institusi</option>
                            <option value="Perorangan" {{ old('institution') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                            <option value="Dealer" {{ old('institution') == 'Dealer' ? 'selected' : '' }}>Dealer</option>
                        </select>
                        @error('institution')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                            <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Admin dapat mengatur semua pengguna</small>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required minlength="6">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Minimal 6 karakter</small>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn-create-user">
                        <i class="fas fa-save me-2"></i>
                        <span>Simpan Pengguna</span>
                    </button>
                    <a href="{{ route('users.index') }}" class="btn-back-dashboard ms-3">
                        <i class="fas fa-times me-2"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(form);
            const userName = formData.get('name');
            const userEmail = formData.get('email');
            
            Swal.fire({
                title: 'Tambah Pengguna?',
                html: `<div style="text-align: left; padding: 10px 0;">
                        <p style="margin-bottom: 8px;"><strong>Nama:</strong> ${userName}</p>
                        <p style="margin-bottom: 8px;"><strong>Email:</strong> ${userEmail}</p>
                        <p style="color: #6b7280; font-size: 14px; margin-top: 10px;">Apakah Anda yakin ingin menambahkan pengguna ini?</p>
                      </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-check me-2"></i>Ya, Tambahkan',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    form.submit();
                }
            });
        });
    }
});
</script>
@endpush

<style>
.page-header-section {
    background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%) !important;
    padding: 45px 40px !important;
    border-radius: 5px !important;
    border: 1px solid #e9ecef !important;
    margin-bottom: 40px !important;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.02) !important;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 5px;
    padding: 12px 16px;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-label {
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.btn-create-user {
    display: inline-flex !important;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-create-user:hover {
    background: linear-gradient(135deg, #b91c1c, #dc2626);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    color: #fff;
}

.btn-back-dashboard {
    display: inline-flex !important;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s;
}

.btn-back-dashboard:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    color: #fff;
}
</style>
@endsection

