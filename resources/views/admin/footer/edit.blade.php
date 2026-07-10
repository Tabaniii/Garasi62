@extends('layouts.admin')

@section('header-title', 'Site Setting')

@section('content')
<style>
    :root {
        --theme-red: #dc2626;
        --theme-red-hover: #b91c1c;
        --theme-red-light: #fef2f2;
    }
    
    /* Custom Red Theme Overrides */
    .text-theme-red { color: var(--theme-red) !important; }
    .bg-theme-red { background-color: var(--theme-red) !important; }
    
    .btn-theme-red {
        background-color: var(--theme-red);
        border-color: var(--theme-red);
        color: white;
    }
    .btn-theme-red:hover {
        background-color: var(--theme-red-hover);
        border-color: var(--theme-red-hover);
        color: white;
    }
    
    .btn-outline-theme-red {
        color: var(--theme-red);
        border-color: var(--theme-red);
    }
    .btn-outline-theme-red:hover {
        background-color: var(--theme-red);
        color: white;
    }

    /* Form Controls */
    .form-control:focus, .form-select:focus {
        border-color: var(--theme-red);
        box-shadow: 0 0 0 0.25rem rgba(220, 38, 38, 0.25);
    }
    .form-check-input:checked {
        background-color: var(--theme-red);
        border-color: var(--theme-red);
    }

    /* Tabs Styling */
    .nav-tabs {
        border-bottom: 2px solid #e5e7eb;
    }
    .nav-tabs .nav-link {
        color: #6b7280;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 1rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .nav-tabs .nav-link:hover {
        color: var(--theme-red);
        border-color: transparent;
        background: var(--theme-red-light);
    }
    .nav-tabs .nav-link.active {
        color: var(--theme-red);
        border-bottom: 2px solid var(--theme-red);
        background: transparent;
        font-weight: 600;
    }
    .nav-tabs .nav-link i {
        margin-right: 0.5rem;
    }

    /* Card Styling */
    .card-settings {
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-top: 4px solid var(--theme-red);
        border-radius: 0.5rem;
    }
    .card-header-settings {
        background-color: white;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0;
    }

    /* Section Headers */
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        padding-left: 1rem;
        border-left: 4px solid var(--theme-red);
    }

    /* Input Group Icons */
    .input-group-text {
        background-color: #f9fafb;
        color: #6b7280;
    }
    .input-group:focus-within .input-group-text {
        border-color: var(--theme-red);
        color: var(--theme-red);
    }
</style>

<div class="container-fluid pb-5">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Site Setting</h1>
            <p class="text-muted small mb-0">Kelola identitas website, konten footer, About Us, dan cabang showroom</p>
        </div>
        <button type="submit" form="siteSettingForm" class="btn btn-theme-red shadow-sm px-4">
            <i class="fas fa-save me-2"></i>Simpan Perubahan
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2 text-success fa-lg"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4" role="alert">
        <div class="d-flex">
            <i class="fas fa-exclamation-circle me-3 mt-1 text-danger fa-lg"></i>
            <div>
                <strong>Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form id="siteSettingForm" action="{{ route('admin.footer.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card card-settings mb-4">
            <div class="card-header card-header-settings pt-3">
                <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-identity" type="button">
                            <i class="fas fa-globe"></i>Identitas
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-footer" type="button">
                            <i class="fas fa-columns"></i>Footer
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-showroom" type="button">
                            <i class="fas fa-store"></i>Showroom
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-about" type="button">
                            <i class="fas fa-info-circle"></i>About Us
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-social" type="button">
                            <i class="fas fa-share-alt"></i>Sosial Media
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="settingsTabsContent">
                    <!-- Tab 1: Identitas Website -->
                    <div class="tab-pane fade show active" id="tab-identity" role="tabpanel">
                        <h5 class="section-title">Identitas Website</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="site_name" class="form-label fw-bold small text-uppercase text-muted">Nama Website</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-signature text-muted"></i></span>
                                    <input type="text" class="form-control" id="site_name" name="site_name"
                                        value="{{ old('site_name', $settings['site_name']) }}" required placeholder="Contoh: Garasi62">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="site_email" class="form-label fw-bold small text-uppercase text-muted">Email Website</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" class="form-control" id="site_email" name="site_email"
                                        value="{{ old('site_email', $settings['site_email']) }}" required placeholder="email@website.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="site_operational_hours" class="form-label fw-bold small text-uppercase text-muted">Jam Operasional</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-clock text-muted"></i></span>
                                    <input type="text" class="form-control" id="site_operational_hours" name="site_operational_hours"
                                        value="{{ old('site_operational_hours', $settings['site_operational_hours']) }}" required placeholder="08:00 - 17:00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold small text-uppercase text-muted">Telepon Utama</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone', $settings['phone']) }}" required placeholder="+62 812 3456 7890">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="site_logo" class="form-label fw-bold small text-uppercase text-muted">Logo Website</label>
                                <div class="p-4 border rounded bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <input type="file" class="form-control mb-2" id="site_logo" name="site_logo" accept="image/*">
                                            <small class="text-muted d-block"><i class="fas fa-info-circle me-1"></i>Format: JPG, PNG, WebP, SVG. Maksimal 5MB</small>
                                        </div>
                                        <div class="col-md-4 mt-3 mt-md-0 text-center">
                                            <div class="p-3 bg-white border rounded shadow-sm d-inline-block">
                                                <img src="{{ asset($settings['site_logo']) }}" alt="{{ $settings['site_name'] }}" class="img-fluid" style="max-height: 60px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="site_favicon" class="form-label fw-bold small text-uppercase text-muted">Favicon Website</label>
                                <div class="p-4 border rounded bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <input type="file" class="form-control mb-2" id="site_favicon" name="site_favicon" accept="image/*">
                                            <small class="text-muted d-block"><i class="fas fa-info-circle me-1"></i>Format: ICO, PNG, SVG. Maksimal 2MB</small>
                                        </div>
                                        <div class="col-md-4 mt-3 mt-md-0 text-center">
                                            <div class="p-3 bg-white border rounded shadow-sm d-inline-block">
                                                <img src="{{ asset($settings['site_favicon']) }}" alt="Favicon" class="img-fluid" style="max-height: 48px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Footer -->
                    <div class="tab-pane fade" id="tab-footer" role="tabpanel">
                        <h5 class="section-title">Konten Footer</h5>
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="contact_title" class="form-label fw-bold small text-uppercase text-muted">Judul Kontak Footer</label>
                                        <input type="text" class="form-control" id="contact_title" name="contact_title"
                                            value="{{ old('contact_title', $settings['contact_title']) }}" required placeholder="Misal: Hubungi Kami Sekarang!">
                                    </div>
                                    <div class="col-12">
                                        <label for="about_text" class="form-label fw-bold small text-uppercase text-muted">Deskripsi Footer</label>
                                        <textarea class="form-control" id="about_text" name="about_text" rows="3"
                                            required placeholder="Tuliskan deskripsi singkat yang menarik di sini...">{{ old('about_text', $settings['about_text']) }}</textarea>
                                        <div class="form-text text-end"><small>Maksimal 500 karakter</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border bg-light shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title text-theme-red fw-bold mb-3"><i class="fas fa-list-ul me-2"></i>Kolom Informasi 1</h6>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Judul Kolom</label>
                                            <input type="text" class="form-control form-control-sm" name="info1_title"
                                                value="{{ old('info1_title', $settings['info1_title']) }}" required>
                                        </div>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label small text-muted mb-0">Daftar Link</label>
                                                <button type="button" class="btn btn-xs btn-sm btn-outline-theme-red add-link-btn" data-target="info1">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div id="info1_container" class="d-flex flex-column gap-2">
                                                <!-- Dynamic Content -->
                                            </div>
                                            <textarea class="form-control form-control-sm font-monospace d-none" name="info1_list" id="info1_list" rows="5"
                                                required>{{ old('info1_list', $settings['info1_list']) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border bg-light shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title text-theme-red fw-bold mb-3"><i class="fas fa-list-ul me-2"></i>Kolom Informasi 2</h6>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Judul Kolom</label>
                                            <input type="text" class="form-control form-control-sm" name="info2_title"
                                                value="{{ old('info2_title', $settings['info2_title']) }}" required>
                                        </div>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label small text-muted mb-0">Daftar Link</label>
                                                <button type="button" class="btn btn-xs btn-sm btn-outline-theme-red add-link-btn" data-target="info2">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div id="info2_container" class="d-flex flex-column gap-2">
                                                <!-- Dynamic Content -->
                                            </div>
                                            <textarea class="form-control form-control-sm font-monospace d-none" name="info2_list" id="info2_list" rows="5"
                                                required>{{ old('info2_list', $settings['info2_list']) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h6 class="mb-0 fw-bold text-gray-800"><i class="fas fa-tags me-2 text-theme-red"></i>Top Brand (Maks. 4)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4 col-md-6">
                                            <label class="form-label small text-muted">Judul Section Brand</label>
                                            <input type="text" class="form-control" name="brands_title"
                                                value="{{ old('brands_title', $settings['brands_title']) }}" required>
                                        </div>
                                        @if($brands->count() === 0)
                                            <div class="alert alert-warning mb-0"><i class="fas fa-info-circle me-2"></i>Belum ada brand di data mobil.</div>
                                        @else
                                            <div class="row row-cols-2 row-cols-md-4 g-3">
                                                @foreach($brands as $brand)
                                                    <div class="col">
                                                        <div class="form-check p-3 border rounded bg-light h-100 position-relative">
                                                            <input class="form-check-input" type="checkbox" name="brands[]" value="{{ $brand }}" id="brand_{{ $loop->index }}"
                                                                {{ in_array($brand, old('brands', $selectedBrands)) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold stretched-link" for="brand_{{ $loop->index }}">
                                                                {{ $brand }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Showroom -->
                    <div class="tab-pane fade" id="tab-showroom" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="section-title mb-0">Jam Operasional</h5>
                                    <button type="button" class="btn btn-sm btn-outline-theme-red" id="addOperationalHour">
                                        <i class="fas fa-plus me-1"></i> Tambah Jadwal
                                    </button>
                                </div>
                                <div id="operationalHoursContainer" class="d-flex flex-column gap-3">
                                    @forelse($operationalHours as $index => $hour)
                                        <div class="card border shadow-sm operational-hour-item">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="card-subtitle text-theme-red fw-bold small"><i class="far fa-clock me-1"></i>Jadwal #{{ $loop->iteration }}</h6>
                                                    <button type="button" class="btn btn-close btn-sm remove-operational-hour" aria-label="Close"></button>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-5">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control form-control-sm" id="op_day_{{ $index }}" name="operational_hours[{{ $index }}][day]" value="{{ $hour['day'] }}" placeholder="Hari" required>
                                                            <label for="op_day_{{ $index }}">Hari</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control form-control-sm" id="op_hours_{{ $index }}" name="operational_hours[{{ $index }}][hours]" value="{{ $hour['hours'] }}" placeholder="Jam" required>
                                                            <label for="op_hours_{{ $index }}">Jam Buka</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="alert alert-light border mb-0 text-center text-muted py-4">
                                            <i class="far fa-calendar-times fa-2x mb-2"></i><br>
                                            Belum ada jam operasional.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="section-title mb-0">Cabang Showroom</h5>
                                    <button type="button" class="btn btn-sm btn-outline-theme-red" id="addShowroom">
                                        <i class="fas fa-plus me-1"></i> Tambah Cabang
                                    </button>
                                </div>
                                <div id="showroomsContainer" class="d-flex flex-column gap-3" style="max-height: 600px; overflow-y: auto;">
                                    @forelse($showrooms as $index => $showroom)
                                        <div class="card border shadow-sm showroom-item">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="card-subtitle text-theme-red fw-bold small"><i class="fas fa-map-marker-alt me-1"></i>Cabang #{{ $loop->iteration }}</h6>
                                                    <button type="button" class="btn btn-close btn-sm remove-showroom" aria-label="Close"></button>
                                                </div>
                                                <div class="vstack gap-2">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control form-control-sm" id="sr_title_{{ $index }}" name="showrooms[{{ $index }}][title]" value="{{ $showroom['title'] }}" placeholder="Nama Cabang" required>
                                                        <label for="sr_title_{{ $index }}">Nama Cabang</label>
                                                    </div>
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control form-control-sm" id="sr_addr_{{ $index }}" name="showrooms[{{ $index }}][address]" value="{{ $showroom['address'] }}" placeholder="Alamat" required>
                                                        <label for="sr_addr_{{ $index }}">Alamat Lengkap</label>
                                                    </div>
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control form-control-sm" id="sr_phone_{{ $index }}" name="showrooms[{{ $index }}][phone]" value="{{ $showroom['phone'] }}" placeholder="Telepon" required>
                                                        <label for="sr_phone_{{ $index }}">No. Telepon</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="alert alert-light border mb-0 text-center text-muted py-4">
                                            <i class="fas fa-store-slash fa-2x mb-2"></i><br>
                                            Belum ada showroom.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: About Us -->
                    <div class="tab-pane fade" id="tab-about" role="tabpanel">
                        <h5 class="section-title">Informasi About Us</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="about_title" class="form-label fw-bold small text-uppercase text-muted">Judul Utama</label>
                                <input type="text" class="form-control" id="about_title" name="about_title"
                                    value="{{ old('about_title', $settings['about_title']) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="about_subtitle" class="form-label fw-bold small text-uppercase text-muted">Subjudul</label>
                                <input type="text" class="form-control" id="about_subtitle" name="about_subtitle"
                                    value="{{ old('about_subtitle', $settings['about_subtitle']) }}" required>
                            </div>
                            <div class="col-12">
                                <label for="about_description" class="form-label fw-bold small text-uppercase text-muted">Deskripsi Lengkap</label>
                                <textarea class="form-control" id="about_description" name="about_description" rows="5"
                                    required>{{ old('about_description', $settings['about_description']) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Gambar Utama</label>
                                <div class="p-4 border rounded bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <input type="file" class="form-control" name="about_image" accept="image/*">
                                            <small class="text-muted d-block mt-1">Format: JPG, PNG, WebP. Maksimal 5MB</small>
                                        </div>
                                        <div class="col-md-4 mt-3 mt-md-0 text-center">
                                            <div class="p-2 bg-white border rounded shadow-sm d-inline-block">
                                                <img src="{{ asset($settings['about_image']) }}" alt="About Image" class="img-fluid" style="max-height: 120px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border shadow-sm">
                                    <div class="card-header bg-white py-3 border-bottom-0">
                                        <h6 class="card-title mb-0 text-theme-red fw-bold"><i class="fas fa-bullseye me-2"></i>Mission</h6>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Judul Mission</label>
                                            <input type="text" class="form-control form-control-sm" name="about_mission_title"
                                                value="{{ old('about_mission_title', $settings['about_mission_title']) }}" required>
                                        </div>
                                        <div>
                                            <label class="form-label small text-muted">Deskripsi Mission</label>
                                            <textarea class="form-control form-control-sm" name="about_mission_text" rows="4"
                                                required>{{ old('about_mission_text', $settings['about_mission_text']) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border shadow-sm">
                                    <div class="card-header bg-white py-3 border-bottom-0">
                                        <h6 class="card-title mb-0 text-theme-red fw-bold"><i class="fas fa-eye me-2"></i>Vision</h6>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Judul Vision</label>
                                            <input type="text" class="form-control form-control-sm" name="about_vision_title"
                                                value="{{ old('about_vision_title', $settings['about_vision_title']) }}" required>
                                        </div>
                                        <div>
                                            <label class="form-label small text-muted">Deskripsi Vision</label>
                                            <textarea class="form-control form-control-sm" name="about_vision_text" rows="4"
                                                required>{{ old('about_vision_text', $settings['about_vision_text']) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h6 class="mb-3 fw-bold text-gray-800 border-bottom pb-2">Fitur Unggulan (3 Item)</h6>
                                <div class="row g-3">
                                    @for($i = 1; $i <= 3; $i++)
                                        <div class="col-md-4">
                                            <div class="card h-100 border shadow-sm">
                                                <div class="card-body">
                                                    <div class="badge bg-theme-red mb-3">Fitur #{{ $i }}</div>
                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted">Judul</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="about_feature_{{ $i }}_title"
                                                            value="{{ old('about_feature_' . $i . '_title', $settings['about_feature_' . $i . '_title']) }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted">Deskripsi</label>
                                                        <textarea class="form-control form-control-sm"
                                                            name="about_feature_{{ $i }}_text" rows="3"
                                                            required>{{ old('about_feature_' . $i . '_text', $settings['about_feature_' . $i . '_text']) }}</textarea>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small text-muted">Icon</label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <input type="file" class="form-control form-control-sm"
                                                                name="about_feature_{{ $i }}_icon" accept="image/*">
                                                            <div class="bg-light p-1 rounded border">
                                                                <img src="{{ asset($settings['about_feature_' . $i . '_icon']) }}"
                                                                    alt="Icon" style="width: 30px; height: 30px; object-fit: contain;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 5: Sosial Media -->
                    <div class="tab-pane fade" id="tab-social" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="section-title mb-0">Link Sosial Media</h5>
                            <button type="button" class="btn btn-sm btn-outline-theme-red" id="addSocialLink">
                                <i class="fas fa-plus me-1"></i> Tambah Link
                            </button>
                        </div>
                        
                        <div class="alert alert-info border-0 bg-light-info shadow-sm mb-4">
                            <i class="fas fa-magic me-2"></i><strong>Smart Detection:</strong> Paste URL sosial media Anda, dan sistem akan otomatis mendeteksi platform dan icon yang sesuai.
                        </div>

                        <div id="socialLinksContainer" class="d-flex flex-column gap-3">
                            @forelse($socialLinks as $index => $social)
                                <div class="card border shadow-sm social-link-item">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="card-subtitle text-theme-red fw-bold small"><i class="fas fa-share-alt me-1"></i>Social Media #{{ $loop->iteration }}</h6>
                                            <button type="button" class="btn btn-close btn-sm remove-social-link" aria-label="Close"></button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control form-control-sm social-platform" 
                                                        name="social_links[{{ $index }}][platform]" 
                                                        value="{{ $social['platform'] }}" 
                                                        placeholder="Platform" required>
                                                    <label>Platform (ex: Facebook)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-floating">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white"><i class="fab fa-{{ $social['icon'] }} social-icon-preview"></i></span>
                                                        <input type="text" class="form-control form-control-sm social-icon" 
                                                            name="social_links[{{ $index }}][icon]" 
                                                            value="{{ $social['icon'] }}" 
                                                            placeholder="Icon Class" required>
                                                    </div>
                                                </div>
                                                <div class="form-text x-small mt-1">Gunakan nama icon FontAwesome (ex: facebook, instagram)</div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-floating">
                                                    <input type="url" class="form-control form-control-sm social-url" 
                                                        name="social_links[{{ $index }}][url]" 
                                                        value="{{ $social['url'] }}" 
                                                        placeholder="URL" required>
                                                    <label>URL Profile</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-light border mb-0 text-center text-muted py-4">
                                    <i class="fas fa-share-alt-square fa-2x mb-2"></i><br>
                                    Belum ada link sosial media.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Helper function for creating elements
            function createOperationalHour(index) {
                return `
                    <div class="card border shadow-sm operational-hour-item animate__animated animate__fadeIn">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="card-subtitle text-theme-red fw-bold small"><i class="far fa-clock me-1"></i>Jadwal Baru</h6>
                                <button type="button" class="btn btn-close btn-sm remove-operational-hour" aria-label="Close"></button>
                            </div>
                            <div class="row g-2">
                                <div class="col-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" name="operational_hours[${index}][day]" placeholder="Hari" required>
                                        <label>Hari</label>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" name="operational_hours[${index}][hours]" placeholder="Jam" required>
                                        <label>Jam Buka</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            function createShowroom(index) {
                return `
                    <div class="card border shadow-sm showroom-item animate__animated animate__fadeIn">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="card-subtitle text-theme-red fw-bold small"><i class="fas fa-map-marker-alt me-1"></i>Cabang Baru</h6>
                                <button type="button" class="btn btn-close btn-sm remove-showroom" aria-label="Close"></button>
                            </div>
                            <div class="vstack gap-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" name="showrooms[${index}][title]" placeholder="Nama Cabang" required>
                                    <label>Nama Cabang</label>
                                </div>
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" name="showrooms[${index}][address]" placeholder="Alamat" required>
                                    <label>Alamat Lengkap</label>
                                </div>
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" name="showrooms[${index}][phone]" placeholder="Telepon" required>
                                    <label>No. Telepon</label>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            function createSocialLink(index) {
                return `
                    <div class="card border shadow-sm social-link-item animate__animated animate__fadeIn">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="card-subtitle text-theme-red fw-bold small"><i class="fas fa-share-alt me-1"></i>Social Media Baru</h6>
                                <button type="button" class="btn btn-close btn-sm remove-social-link" aria-label="Close"></button>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm social-platform" 
                                            name="social_links[${index}][platform]" 
                                            placeholder="Platform" required>
                                        <label>Platform (ex: Facebook)</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="fab fa-share-alt social-icon-preview"></i></span>
                                            <input type="text" class="form-control form-control-sm social-icon" 
                                                name="social_links[${index}][icon]" 
                                                placeholder="Icon Class" required>
                                        </div>
                                    </div>
                                    <div class="form-text x-small mt-1">Gunakan nama icon FontAwesome</div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="url" class="form-control form-control-sm social-url" 
                                            name="social_links[${index}][url]" 
                                            placeholder="URL" required>
                                        <label>URL Profile</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Footer Links Logic
            function createLinkItem(label = '', url = '') {
                return `
                    <div class="input-group input-group-sm link-item animate__animated animate__fadeIn">
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" placeholder="Label" value="${label}" data-type="label">
                            <label>Label</label>
                        </div>
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" placeholder="URL" value="${url}" data-type="url">
                            <label>URL</label>
                        </div>
                        <button class="btn btn-outline-danger remove-link" type="button"><i class="fas fa-trash-alt"></i></button>
                    </div>
                `;
            }

            function updateHiddenInput(containerId, hiddenInputId) {
                const container = document.getElementById(containerId);
                const hiddenInput = document.getElementById(hiddenInputId);
                const items = container.querySelectorAll('.link-item');
                let values = [];
                items.forEach(item => {
                    const label = item.querySelector('[data-type="label"]').value.trim();
                    const url = item.querySelector('[data-type="url"]').value.trim();
                    if (label || url) {
                        values.push(`${label}|${url}`);
                    }
                });
                hiddenInput.value = values.join('\n');
            }

            function initLinkList(containerId, hiddenInputId) {
                const container = document.getElementById(containerId);
                const hiddenInput = document.getElementById(hiddenInputId);
                const rawValue = hiddenInput.value;

                // Populate initial data
                if (rawValue) {
                    const lines = rawValue.split(/\r\n|\n|\\n/);
                    lines.forEach(line => {
                        if (line.trim()) {
                            const parts = line.split('|');
                            const label = parts[0] || '';
                            const url = parts.slice(1).join('|') || '';
                            container.insertAdjacentHTML('beforeend', createLinkItem(label, url));
                        }
                    });
                }

                // Add event listeners for inputs to update hidden field
                container.addEventListener('input', () => updateHiddenInput(containerId, hiddenInputId));

                // Remove button listener
                container.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-link')) {
                        e.target.closest('.link-item').remove();
                        updateHiddenInput(containerId, hiddenInputId);
                    }
                });
            }

            // Initialize both lists
            initLinkList('info1_container', 'info1_list');
            initLinkList('info2_container', 'info2_list');

            // Add button listeners
            document.querySelectorAll('.add-link-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target') + '_container';
                    const hiddenId = this.getAttribute('data-target') + '_list';
                    document.getElementById(targetId).insertAdjacentHTML('beforeend', createLinkItem());
                });
            });

            // Operational Hours Logic
            const operationalHoursContainer = document.getElementById('operationalHoursContainer');
            document.getElementById('addOperationalHour').addEventListener('click', function () {
                const index = Date.now();
                const emptyState = operationalHoursContainer.querySelector('.alert');
                if (emptyState) emptyState.remove();
                operationalHoursContainer.insertAdjacentHTML('beforeend', createOperationalHour(index));
            });
            operationalHoursContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-operational-hour') || e.target.closest('.remove-operational-hour')) {
                    const item = e.target.closest('.operational-hour-item');
                    if(item) {
                        item.remove();
                        if(operationalHoursContainer.children.length === 0) {
                             operationalHoursContainer.innerHTML = `
                                <div class="alert alert-light border mb-0 text-center text-muted py-4">
                                    <i class="far fa-calendar-times fa-2x mb-2"></i><br>
                                    Belum ada jam operasional.
                                </div>
                             `;
                        }
                    }
                }
            });

            // Showrooms Logic
            const showroomsContainer = document.getElementById('showroomsContainer');
            document.getElementById('addShowroom').addEventListener('click', function () {
                const index = Date.now();
                const emptyState = showroomsContainer.querySelector('.alert');
                if (emptyState) emptyState.remove();
                showroomsContainer.insertAdjacentHTML('beforeend', createShowroom(index));
            });
            showroomsContainer.addEventListener('click', function (e) {
                 if (e.target.classList.contains('remove-showroom') || e.target.closest('.remove-showroom')) {
                    const item = e.target.closest('.showroom-item');
                    if(item) {
                        item.remove();
                        if(showroomsContainer.children.length === 0) {
                            showroomsContainer.innerHTML = `
                                <div class="alert alert-light border mb-0 text-center text-muted py-4">
                                    <i class="fas fa-store-slash fa-2x mb-2"></i><br>
                                    Belum ada showroom.
                                </div>
                            `;
                        }
                    }
                }
            });

            // Social Links Logic
            const socialLinksContainer = document.getElementById('socialLinksContainer');
            
            // Add Button
            document.getElementById('addSocialLink').addEventListener('click', function () {
                const index = Date.now();
                const emptyState = socialLinksContainer.querySelector('.alert');
                if (emptyState) emptyState.remove();
                socialLinksContainer.insertAdjacentHTML('beforeend', createSocialLink(index));
            });

            // Remove Button
            socialLinksContainer.addEventListener('click', function (e) {
                 if (e.target.classList.contains('remove-social-link') || e.target.closest('.remove-social-link')) {
                    const item = e.target.closest('.social-link-item');
                    if(item) {
                        item.remove();
                        if(socialLinksContainer.children.length === 0) {
                            socialLinksContainer.innerHTML = `
                                <div class="alert alert-light border mb-0 text-center text-muted py-4">
                                    <i class="fas fa-share-alt-square fa-2x mb-2"></i><br>
                                    Belum ada link sosial media.
                                </div>
                            `;
                        }
                    }
                }
            });

            // Smart Detection & Icon Preview Logic
            // Debounce helper
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            // Smart Detection Logic
            const handleUrlInput = debounce(async function(e) {
                const urlInput = e.target;
                const url = urlInput.value.trim();
                if (!url) return;

                const item = urlInput.closest('.social-link-item');
                const platformInput = item.querySelector('.social-platform');
                const iconInput = item.querySelector('.social-icon');
                const iconPreview = item.querySelector('.social-icon-preview');

                // Show loading state
                const originalIconClass = iconPreview.className;
                iconPreview.className = 'fas fa-spinner fa-spin social-icon-preview';

                try {
                    const response = await fetch('{{ route("admin.footer.fetch-og") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ url: url })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        platformInput.value = data.platform;
                        iconInput.value = data.icon;
                        iconPreview.className = `fab fa-${data.icon} social-icon-preview`;
                    } else {
                        runRegexFallback(url, platformInput, iconInput, iconPreview);
                    }
                } catch (error) {
                    console.error('OG Fetch Error:', error);
                    runRegexFallback(url, platformInput, iconInput, iconPreview);
                }
            }, 800);

            function runRegexFallback(url, platformInput, iconInput, iconPreview) {
                const platforms = [
                    { regex: /facebook\.com/, name: 'Facebook', icon: 'facebook' },
                    { regex: /twitter\.com|x\.com/, name: 'Twitter', icon: 'twitter' },
                    { regex: /instagram\.com/, name: 'Instagram', icon: 'instagram' },
                    { regex: /linkedin\.com/, name: 'LinkedIn', icon: 'linkedin' },
                    { regex: /youtube\.com/, name: 'YouTube', icon: 'youtube' },
                    { regex: /tiktok\.com/, name: 'TikTok', icon: 'tiktok' },
                    { regex: /pinterest\.com/, name: 'Pinterest', icon: 'pinterest' },
                    { regex: /github\.com/, name: 'GitHub', icon: 'github' },
                    { regex: /whatsapp\.com|wa\.me/, name: 'WhatsApp', icon: 'whatsapp' },
                    { regex: /t\.me|telegram\.org/, name: 'Telegram', icon: 'telegram' },
                    { regex: /skype\.com/, name: 'Skype', icon: 'skype' },
                    { regex: /google\.com/, name: 'Google', icon: 'google' },
                ];
                
                let found = false;
                for (const p of platforms) {
                    if (p.regex.test(url)) {
                        platformInput.value = p.name;
                        iconInput.value = p.icon;
                        iconPreview.className = `fab fa-${p.icon} social-icon-preview`;
                        found = true;
                        break;
                    }
                }
                
                if (!found) {
                    // Stop spinner if no match found
                    const currentIcon = iconInput.value || 'share-alt';
                    iconPreview.className = `fab fa-${currentIcon} social-icon-preview`;
                }
            }

            socialLinksContainer.addEventListener('input', function(e) {
                // Handle URL Input (Smart Detection)
                if (e.target.classList.contains('social-url')) {
                    handleUrlInput(e);
                }

                // Handle Icon Input (Preview Update)
                if (e.target.classList.contains('social-icon')) {
                    const iconClass = e.target.value;
                    const item = e.target.closest('.social-link-item');
                    const iconPreview = item.querySelector('.social-icon-preview');
                    let cleanIcon = iconClass.replace('fa-', '').replace('fab ', '').replace('fas ', '');
                    iconPreview.className = `fab fa-${cleanIcon} social-icon-preview`;
                }
            });
        });
    </script>
@endpush
