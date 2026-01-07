@extends('template.temp')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Dashboard Section Begin -->
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
                <div class="bg-light p-4 rounded">
                    <h2 class="text-danger mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="text-muted mb-0">Ini adalah halaman dashboard Anda. Di sini Anda dapat mengelola akun dan aktivitas Anda.</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="text-danger me-3" style="font-size: 48px; line-height: 1;">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2 fw-bold">Profil Saya</h5>
                                <p class="card-text text-muted mb-0 small">Kelola informasi profil Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="text-danger me-3" style="font-size: 48px; line-height: 1;">
                                <i class="fa fa-car"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2 fw-bold">Mobil Saya</h5>
                                <p class="card-text text-muted mb-0 small">Lihat dan kelola daftar mobil</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="text-danger me-3" style="font-size: 48px; line-height: 1;">
                                <i class="fa fa-cog"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2 fw-bold">Pengaturan</h5>
                                <p class="card-text text-muted mb-0 small">Ubah pengaturan akun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="text-danger mb-4 fw-bold">Informasi Akun</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex border-bottom pb-3 mb-3">
                                    <div class="text-muted fw-semibold me-3" style="min-width: 150px;">Nama:</div>
                                    <div class="text-dark">{{ Auth::user()->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex border-bottom pb-3 mb-3">
                                    <div class="text-muted fw-semibold me-3" style="min-width: 150px;">Email:</div>
                                    <div class="text-dark">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex border-bottom pb-3 mb-3">
                                    <div class="text-muted fw-semibold me-3" style="min-width: 150px;">Nomor Telepon:</div>
                                    <div class="text-dark">{{ Auth::user()->phone }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex border-bottom pb-3 mb-3">
                                    <div class="text-muted fw-semibold me-3" style="min-width: 150px;">Jenis Kelamin:</div>
                                    <div class="text-dark">{{ Auth::user()->gender }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex border-bottom pb-3 mb-3">
                                    <div class="text-muted fw-semibold me-3" style="min-width: 150px;">Kota:</div>
                                    <div class="text-dark">{{ Auth::user()->city }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex border-bottom pb-3 mb-3">
                                    <div class="text-muted fw-semibold me-3" style="min-width: 150px;">Institusi:</div>
                                    <div class="text-dark">{{ Auth::user()->institution }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="site-btn" style="white-space: nowrap; text-decoration: none; border-radius: 5px;">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Dashboard Section End -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
