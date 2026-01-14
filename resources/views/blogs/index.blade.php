@extends('layouts.admin')

@section('header-title', 'Kelola Blog')

@section('content')
<style>
/* Global Animations */
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

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Header */
.page-header-section {
    background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%) !important;
    padding: 45px 40px !important;
    border-radius: 24px !important;
    border: 1px solid #e9ecef !important;
    margin-bottom: 40px !important;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.02) !important;
    position: relative;
    overflow: hidden;
}

.page-header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #dc2626, #ef4444, #f87171, #ef4444, #dc2626);
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.page-header-content {
    display: flex !important;
    justify-content: space-between;
    align-items: center;
    gap: 25px;
    position: relative;
    z-index: 1;
}

.page-title-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.page-header-section .page-title {
    font-size: 36px !important;
    font-weight: 900 !important;
    background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 !important;
    letter-spacing: -0.5px;
}

.page-badge {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.page-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
    font-weight: 500;
}

.btn-add-new {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 14px;
    border: none;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
}

.btn-add-new:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
    color: #fff;
}

/* Blog Grid */
.blogs-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)) !important;
    gap: 25px !important;
    margin-bottom: 45px !important;
}

@media (max-width: 768px) {
    .blogs-grid {
        grid-template-columns: 1fr !important;
    }
}

.blog-card {
    background: #fff !important;
    border-radius: 16px !important;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.04) !important;
    border: 1px solid #e9ecef !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex !important;
    flex-direction: column;
}

.blog-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 60px rgba(220, 38, 38, 0.2), 0 0 0 1px rgba(220, 38, 38, 0.1) !important;
    border-color: #dc2626 !important;
}

.blog-card-image {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #f5f5f5 0%, #e9ecef 100%);
}

.blog-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s;
}

.blog-card:hover .blog-card-image img {
    transform: scale(1.1);
}

.blog-status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-published {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: #fff;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.badge-draft {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    color: #fff;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.blog-card-body {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.blog-title {
    font-size: 18px !important;
    font-weight: 800 !important;
    color: #1a1a1a;
    margin: 0 0 10px 0 !important;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-excerpt {
    font-size: 13px;
    color: #6b7280;
    margin: 0 0 15px 0;
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.blog-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 0;
    border-top: 1px solid #f3f4f6;
    font-size: 12px;
    color: #9ca3af;
}

.blog-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.blog-meta-item i {
    color: #dc2626;
}

.blog-card-actions {
    display: flex !important;
    gap: 8px !important;
    margin-top: 15px !important;
    padding-top: 15px !important;
    border-top: 2px solid #f3f4f6 !important;
}

.btn-action {
    flex: 1 !important;
    padding: 10px 12px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 12px !important;
    border: 2px solid;
    cursor: pointer;
    display: flex !important;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-edit-action {
    background: linear-gradient(135deg, #fff, #fafafa) !important;
    color: #f59e0b !important;
    border-color: #fbbf24 !important;
}

.btn-edit-action:hover {
    background: linear-gradient(135deg, #f59e0b, #fbbf24) !important;
    border-color: #f59e0b !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.btn-delete-action {
    background: linear-gradient(135deg, #fff, #fafafa) !important;
    color: #dc2626 !important;
    border-color: #ef4444 !important;
}

.btn-delete-action:hover {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    border-color: #dc2626 !important;
    color: #fff !important;
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border-radius: 20px !important;
    padding: 80px 40px !important;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06) !important;
    border: 2px dashed #e9ecef !important;
}

.empty-state-icon {
    font-size: 64px;
    color: #dc2626;
    margin-bottom: 20px;
}

.empty-state-title {
    font-size: 24px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.empty-state-text {
    color: #6b7280;
    margin-bottom: 25px;
}

.btn-empty-state {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-empty-state:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
    color: #fff;
}

.btn-back-dashboard {
    background: linear-gradient(135deg, #1a1a1a, #4a4a4a);
    color: #fff;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-back-dashboard:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    color: #fff;
}

.alert-custom {
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2) !important;
    margin-bottom: 25px !important;
}
</style>

<div class="page-header-section mb-5">
    <div class="page-header-content">
        <div class="page-header-text">
            <div class="page-title-wrapper">
                <h1 class="page-title">Kelola Blog</h1>
                @if($blogs->count() > 0)
                <span class="page-badge">{{ $blogs->count() }} Blog</span>
                @endif
            </div>
            <p class="page-subtitle">
                <i class="fas fa-info-circle me-2"></i>Kelola artikel blog yang dapat dilihat oleh semua pengunjung
            </p>
        </div>
        <a href="{{ route('blogs.admin.create') }}" class="btn-add-new">
            <i class="fas fa-plus"></i>
            <span>Tambah Blog Baru</span>
        </a>
    </div>
</div>

@if($blogs->count() > 0)
<div class="blogs-grid">
    @foreach($blogs as $index => $blog)
    <div class="blog-card animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s;">
        <div class="blog-card-image">
            @if($blog->image)
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
            @else
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                <i class="fas fa-image" style="font-size: 48px;"></i>
            </div>
            @endif
            <span class="blog-status-badge {{ $blog->status == 'published' ? 'badge-published' : 'badge-draft' }}">
                {{ $blog->status == 'published' ? 'Published' : 'Draft' }}
            </span>
        </div>
        <div class="blog-card-body">
            <h5 class="blog-title">{{ $blog->title }}</h5>
            @if($blog->excerpt)
            <p class="blog-excerpt">{{ $blog->excerpt }}</p>
            @else
            <p class="blog-excerpt">{{ Str::limit(strip_tags($blog->content), 150) }}</p>
            @endif
            <div class="blog-meta">
                <div class="blog-meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $blog->author }}</span>
                </div>
                <div class="blog-meta-item">
                    <i class="fas fa-comments"></i>
                    <span>{{ $blog->comment_count }} Komentar</span>
                </div>
                @if($blog->published_at)
                <div class="blog-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $blog->published_at->format('d M Y') }}</span>
                </div>
                @endif
            </div>
            <div class="blog-card-actions">
                <a href="{{ route('blogs.admin.edit', $blog->id) }}" class="btn-action btn-edit-action">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
                <button type="button" class="btn-action btn-delete-action w-100" onclick="confirmDeleteBlog({{ $blog->id }}, '{{ addslashes($blog->title) }}')">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
                <form id="delete-blog-form-{{ $blog->id }}" action="{{ route('blogs.admin.destroy', $blog->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="empty-state animate-fade-in">
    <div class="empty-state-icon">
        <i class="fas fa-blog"></i>
    </div>
    <h4 class="empty-state-title">Belum ada blog</h4>
    <p class="empty-state-text">Mulai dengan menambahkan blog pertama Anda</p>
    <a href="{{ route('blogs.admin.create') }}" class="btn-empty-state">
        <i class="fas fa-plus"></i>
        <span>Tambah Blog Baru</span>
    </a>
</div>
@endif

<div class="mt-5 pt-4 border-top">
    <a href="{{ route('dashboard') }}" class="btn-back-dashboard">
        <i class="fas fa-arrow-left me-2"></i>
        <span>Kembali ke Dashboard</span>
    </a>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteBlog(blogId, blogTitle) {
    Swal.fire({
        title: '<strong style="color: #1a1a1a;">Hapus Blog?</strong>',
        html: `<div style="text-align: left; padding: 10px 0;">
            <p style="color: #6b7280; margin-bottom: 15px;">Anda akan menghapus blog berikut:</p>
            <div style="background: #f9fafb; padding: 15px; border-radius: 8px; border-left: 4px solid #dc2626; margin-bottom: 15px;">
                <strong style="color: #1a1a1a; display: block; margin-bottom: 5px;">${blogTitle}</strong>
            </div>
            <p style="color: #dc2626; font-size: 14px; margin: 0;">
                <i class="fas fa-exclamation-triangle"></i> Tindakan ini tidak dapat dibatalkan!
            </p>
        </div>`,
        icon: 'warning',
        iconColor: '#dc2626',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus Blog',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            confirmButton: 'swal2-confirm-custom',
            cancelButton: 'swal2-cancel-custom',
            title: 'swal2-title-custom',
            htmlContainer: 'swal2-html-container-custom'
        },
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                html: 'Mohon tunggu, blog sedang dihapus.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form
            document.getElementById('delete-blog-form-' + blogId).submit();
        }
    });
}
</script>

<style>
/* SweetAlert2 Custom Styling */
.swal2-popup-custom {
    border-radius: 16px !important;
    padding: 30px !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
    border: 1px solid #e9ecef !important;
}

.swal2-title-custom {
    font-size: 24px !important;
    font-weight: 800 !important;
    color: #1a1a1a !important;
    margin-bottom: 20px !important;
}

.swal2-html-container-custom {
    font-size: 14px !important;
    color: #6b7280 !important;
    line-height: 1.6 !important;
}

.swal2-confirm-custom {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    color: #fff !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border: none !important;
    transition: all 0.3s !important;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3) !important;
}

.swal2-confirm-custom:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4) !important;
}

.swal2-cancel-custom {
    background: linear-gradient(135deg, #fff, #fafafa) !important;
    color: #6b7280 !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border: 2px solid #e9ecef !important;
    transition: all 0.3s !important;
}

.swal2-cancel-custom:hover {
    background: linear-gradient(135deg, #f9fafb, #f3f4f6) !important;
    border-color: #d1d5db !important;
    transform: translateY(-2px) !important;
}

.swal2-icon.swal2-warning {
    border-color: #dc2626 !important;
    color: #dc2626 !important;
}
</style>
@endsection

