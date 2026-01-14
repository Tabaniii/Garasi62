@extends('layouts.admin')

@section('header-title', 'Kelola Testimoni')

@section('content')
<div class="page-header-section mb-4">
    <div class="page-header-content">
        <div>
            <div class="page-title-wrapper">
                <h2 class="page-title">Kelola Testimoni</h2>
                <span class="page-badge">Manajemen Konten</span>
            </div>
            <p class="page-subtitle">Tambah, ubah, atau hapus testimoni yang tampil di halaman About.</p>
        </div>
        <a href="{{ route('testimonials.admin.create') }}" class="btn-add-new-testimonial">
            <div class="btn-add-new-icon">
                <i class="fas fa-plus"></i>
            </div>
            <span class="btn-add-new-text">Tambah Testimoni</span>
            <div class="btn-add-new-ripple"></div>
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card testimonial-admin-card">
    <div class="card-body">
        <div class="testimonial-header-section">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="mb-1" style="font-weight: 700; color: #1a1a1a; font-size: 20px;">Daftar Testimoni</h5>
                    <p class="text-muted mb-0" style="font-size: 14px;">Kelola semua testimoni yang ditampilkan di website</p>
                </div>
                <div class="testimonial-stats">
                    <div class="stat-badge">
                        <i class="fas fa-quote-right me-1"></i>
                        <span>Total: <strong>{{ $testimonials->count() }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        @if($testimonials->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-quote-right"></i>
                </div>
                <h5 class="empty-state-title">Belum Ada Testimoni</h5>
                <p class="empty-state-text">Mulai dengan menambahkan testimoni pertama untuk ditampilkan di halaman About.</p>
                <a href="{{ route('testimonials.admin.create') }}" class="btn btn-danger btn-lg mt-3">
                    <i class="fas fa-plus me-2"></i>Tambah Testimoni Pertama
                </a>
            </div>
        @else
            <div class="testimonials-grid-admin">
                @foreach($testimonials as $testimonial)
                <div class="testimonial-card-admin">
                    <div class="testimonial-card-header">
                        <div class="testimonial-avatar-section">
                            @if($testimonial->image)
                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="testimonial-avatar-img">
                            @else
                                <div class="testimonial-avatar-placeholder">
                                    {{ strtoupper(mb_substr($testimonial->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="testimonial-status-badge">
                            @if($testimonial->is_active)
                                <span class="badge-status active">
                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge-status inactive">
                                    <i class="fas fa-times-circle me-1"></i>Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="testimonial-card-body">
                        <h6 class="testimonial-name-admin">{{ $testimonial->name }}</h6>
                        @if($testimonial->position || $testimonial->company)
                            <p class="testimonial-role-admin">
                                @if($testimonial->position)
                                    {{ $testimonial->position }}
                                    @if($testimonial->company)
                                        <span class="text-muted">•</span>
                                    @endif
                                @endif
                                @if($testimonial->company)
                                    {{ $testimonial->company }}
                                @endif
                            </p>
                        @endif
                        
                        <div class="testimonial-rating-admin">
                            @for($i = 0; $i < $testimonial->rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @for($i = $testimonial->rating; $i < 5; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                            <span class="rating-text">{{ $testimonial->rating }}/5</span>
                        </div>
                        
                        <p class="testimonial-message-admin">
                            <i class="fas fa-quote-left quote-icon"></i>
                            {{ Str::limit($testimonial->message, 100) }}
                            @if(strlen($testimonial->message) > 100)
                                <span class="text-muted">...</span>
                            @endif
                        </p>
                        
                        <div class="testimonial-meta">
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                {{ $testimonial->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="testimonial-card-footer">
                        <a href="{{ route('testimonials.admin.edit', $testimonial) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                            <span>Edit</span>
                        </a>
                        <form action="{{ route('testimonials.admin.destroy', $testimonial) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Hapus">
                                <i class="fas fa-trash"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.testimonial-admin-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.testimonial-header-section {
    padding: 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-bottom: 2px solid #f0f0f0;
    margin: -20px -20px 24px -20px;
}

.testimonial-stats .stat-badge {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 24px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state-icon i {
    font-size: 48px;
    color: #dc2626;
    opacity: 0.3;
}

.empty-state-title {
    font-size: 24px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.empty-state-text {
    font-size: 16px;
    color: #6b7280;
    max-width: 500px;
    margin: 0 auto;
}

.testimonials-grid-admin {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
}

.testimonial-card-admin {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.testimonial-card-admin::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #dc2626, #991b1b);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s;
}

.testimonial-card-admin:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(220, 38, 38, 0.15);
    border-color: #dc2626;
}

.testimonial-card-admin:hover::before {
    transform: scaleX(1);
}

.testimonial-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.testimonial-avatar-section {
    position: relative;
}

.testimonial-avatar-img {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #dc2626;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    transition: transform 0.3s;
}

.testimonial-card-admin:hover .testimonial-avatar-img {
    transform: scale(1.1);
}

.testimonial-avatar-placeholder {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 24px;
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    transition: transform 0.3s;
}

.testimonial-card-admin:hover .testimonial-avatar-placeholder {
    transform: scale(1.1);
}

.badge-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.badge-status.active {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: white;
}

.badge-status.inactive {
    background: #e5e7eb;
    color: #6b7280;
}

.testimonial-card-body {
    margin-bottom: 20px;
}

.testimonial-name-admin {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 6px 0;
}

.testimonial-role-admin {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 12px 0;
}

.testimonial-rating-admin {
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.testimonial-rating-admin i {
    color: #fbbf24;
    font-size: 16px;
}

.testimonial-rating-admin .far {
    color: #e5e7eb;
}

.rating-text {
    margin-left: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
}

.testimonial-message-admin {
    font-size: 14px;
    line-height: 1.7;
    color: #4b5563;
    margin: 0 0 16px 0;
    font-style: italic;
    position: relative;
    padding-left: 24px;
}

.quote-icon {
    position: absolute;
    left: 0;
    top: 0;
    color: #dc2626;
    opacity: 0.2;
    font-size: 20px;
}

.testimonial-meta {
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.testimonial-card-footer {
    display: flex;
    gap: 8px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.btn-action {
    flex: 1;
    padding: 10px 16px;
    border-radius: 10px;
    border: none;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s;
    cursor: pointer;
    text-decoration: none;
}

.btn-edit {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.btn-edit:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    color: white;
}

/* Button Add New Testimonial */
.btn-add-new-testimonial {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
    border: none;
    cursor: pointer;
    z-index: 1;
}

.btn-add-new-testimonial::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    transition: left 0.5s;
    z-index: -1;
}

.btn-add-new-testimonial:hover::before {
    left: 0;
}

.btn-add-new-testimonial:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(220, 38, 38, 0.4);
    color: white;
}

.btn-add-new-testimonial:active {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
}

.btn-add-new-icon {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    position: relative;
    z-index: 1;
}

.btn-add-new-testimonial:hover .btn-add-new-icon {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg) scale(1.1);
}

.btn-add-new-icon i {
    font-size: 16px;
    transition: transform 0.3s;
}

.btn-add-new-text {
    position: relative;
    z-index: 1;
    letter-spacing: 0.3px;
}

.btn-add-new-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    width: 0;
    height: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
    transition: width 0.6s, height 0.6s, opacity 0.6s;
    opacity: 0;
}

.btn-add-new-testimonial:hover .btn-add-new-ripple {
    width: 300px;
    height: 300px;
    opacity: 0;
}

.btn-add-new-testimonial:active .btn-add-new-ripple {
    width: 200px;
    height: 200px;
    opacity: 0.3;
    transition: width 0s, height 0s, opacity 0.3s;
}

@media (max-width: 768px) {
    .testimonials-grid-admin {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .testimonial-card-admin {
        padding: 20px;
    }
    
    .testimonial-header-section {
        padding: 20px;
        margin: -20px -20px 20px -20px;
    }
    
    .btn-add-new-testimonial {
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .btn-add-new-icon {
        width: 28px;
        height: 28px;
    }
    
    .btn-add-new-icon i {
        font-size: 14px;
    }
}
</style>
@endsection

