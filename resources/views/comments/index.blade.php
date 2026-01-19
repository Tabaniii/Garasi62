@extends('layouts.admin')

@section('header-title', 'Kelola Komentar')

@section('content')
<style>
.comment-card {
    background: #fff;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s;
}

.comment-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    border-color: #dc2626;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 12px;
}

.comment-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
    font-size: 18px;
}

.comment-info h6 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
}

.comment-info p {
    margin: 0;
    font-size: 12px;
    color: #6b7280;
}

.comment-status {
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-approved {
    background: #d1fae5;
    color: #059669;
}

.status-rejected {
    background: #fee2e2;
    color: #dc2626;
}

.comment-content {
    margin-bottom: 15px;
    padding: 15px;
    background: #f9fafb;
    border-radius: 5px;
    border-left: 3px solid #dc2626;
}

.comment-actions {
    display: flex;
    gap: 10px;
}

.btn-action-sm {
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-approve {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: #fff;
}

.btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-reject {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
}

.btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.btn-delete {
    background: #6b7280;
    color: #fff;
}

.btn-delete:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

.stats-card {
    background: #fff;
    border-radius: 5px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.stats-number {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 5px;
}

.stats-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 600;
}

.reply-indicator {
    margin-left: 60px;
    padding-left: 15px;
    border-left: 3px solid #3b82f6;
    font-size: 12px;
    color: #3b82f6;
    margin-top: 10px;
}
</style>

<!-- Toast Notification Container -->

<div class="page-header-section mb-4">
    <div class="page-header-content">
        <div class="page-header-text">
            <div class="page-title-wrapper">
                <h1 class="page-title">Kelola Komentar</h1>
            </div>
            <p class="page-subtitle">
                <i class="fas fa-info-circle me-2"></i>Review dan kelola komentar dari pengunjung blog
            </p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="stats-card">
            <div class="stats-number text-warning">{{ $pendingCount }}</div>
            <div class="stats-label">Menunggu Persetujuan</div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="stats-card">
            <div class="stats-number text-success">{{ $approvedCount }}</div>
            <div class="stats-label">Disetujui</div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="stats-card">
            <div class="stats-number text-danger">{{ $rejectedCount }}</div>
            <div class="stats-label">Ditolak</div>
        </div>
    </div>
</div>

<!-- Comments List -->
@if($comments->count() > 0)
<div class="comments-list">
    @foreach($comments as $comment)
    <div class="comment-card" data-comment-id="{{ $comment->id }}">
        <div class="comment-header">
            <div class="comment-author">
                <div class="comment-avatar">
                    {{ strtoupper(substr($comment->commenter_name, 0, 1)) }}
                </div>
                <div class="comment-info">
                    <h6>{{ $comment->commenter_name }}</h6>
                    <p>
                        <i class="fas fa-envelope me-1"></i>{{ $comment->commenter_email }}
                        @if($comment->blog)
                        | <i class="fas fa-file-alt me-1"></i><a href="{{ route('blog.show', $comment->blog->slug) }}" target="_blank">{{ $comment->blog->title }}</a>
                        @endif
                        | <i class="fas fa-calendar me-1"></i>{{ $comment->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
            <span class="comment-status status-{{ $comment->status }}">
                {{ ucfirst($comment->status) }}
            </span>
        </div>
        
        @if($comment->parent)
        <div class="reply-indicator">
            <i class="fas fa-reply me-1"></i>Membalas ke: {{ $comment->parent->commenter_name }}
        </div>
        @endif
        
        <div class="comment-content">
            {{ $comment->comment }}
        </div>
        
        <div class="comment-actions">
            @if($comment->status == 'pending')
            <button type="button" class="btn-action-sm btn-approve approve-btn" data-comment-id="{{ $comment->id }}" data-commenter="{{ $comment->commenter_name }}">
                <i class="fas fa-check me-1"></i>Setujui
            </button>
            <button type="button" class="btn-action-sm btn-reject reject-btn" data-comment-id="{{ $comment->id }}" data-commenter="{{ $comment->commenter_name }}">
                <i class="fas fa-times me-1"></i>Tolak
            </button>
            @endif
            
            <button type="button" class="btn-action-sm btn-delete delete-btn" data-comment-id="{{ $comment->id }}" data-commenter="{{ $comment->commenter_name }}">
                <i class="fas fa-trash me-1"></i>Hapus
            </button>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $comments->links() }}
</div>
@else
<div class="text-center py-5">
    <div class="text-muted">
        <i class="fas fa-comments fa-3x mb-3"></i>
        <p>Belum ada komentar</p>
    </div>
</div>
@endif

<div class="mt-5 pt-4 border-top">
    <a href="{{ route('dashboard') }}" class="btn-back-dashboard">
        <i class="fas fa-arrow-left me-2"></i>
        <span>Kembali ke Dashboard</span>
    </a>
</div>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* SweetAlert2 Custom Styling */
.swal2-popup {
    border-radius: 5px !important;
    padding: 30px !important;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
}

.swal2-title {
    font-size: 24px !important;
    font-weight: 800 !important;
    color: #1a1a1a !important;
    margin-bottom: 15px !important;
}

.swal2-html-container {
    font-size: 15px !important;
    color: #4b5563 !important;
    line-height: 1.6 !important;
}

.swal2-confirm {
    border-radius: 5px !important;
    padding: 12px 24px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    transition: all 0.3s !important;
}

.swal2-confirm:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2) !important;
}

.swal2-cancel {
    border-radius: 5px !important;
    padding: 12px 24px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    transition: all 0.3s !important;
}

.swal2-cancel:hover {
    transform: translateY(-2px) !important;
}

.swal2-icon {
    margin: 0 auto 20px !important;
    width: 64px !important;
    height: 64px !important;
}

.swal2-icon.swal2-question {
    border-color: #3b82f6 !important;
    color: #3b82f6 !important;
}

.swal2-icon.swal2-warning {
    border-color: #f59e0b !important;
    color: #f59e0b !important;
}

.swal2-icon.swal2-error {
    border-color: #ef4444 !important;
    color: #ef4444 !important;
}

.swal2-icon.swal2-success {
    border-color: #10b981 !important;
    color: #10b981 !important;
}

.swal2-loader {
    border-color: #dc2626 transparent #dc2626 transparent !important;
}

/* Enhanced Delete Confirmation Styling */
.swal2-popup-custom-delete {
    border-radius: 5px !important;
    padding: 35px !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
    border: 1px solid #e9ecef !important;
    max-width: 550px !important;
}

.swal2-title-custom-delete {
    font-size: 28px !important;
    font-weight: 900 !important;
    color: #1a1a1a !important;
    margin-bottom: 20px !important;
    letter-spacing: -0.5px !important;
}

.swal2-html-container-custom-delete {
    font-size: 14px !important;
    color: #6b7280 !important;
    line-height: 1.6 !important;
    text-align: left !important;
}

.swal2-confirm-custom-delete {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    color: #fff !important;
    padding: 14px 28px !important;
    border-radius: 5px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border: none !important;
    transition: all 0.3s !important;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3) !important;
}

.swal2-confirm-custom-delete:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4) !important;
    background: linear-gradient(135deg, #b91c1c, #dc2626) !important;
}

.swal2-cancel-custom-delete {
    background: linear-gradient(135deg, #fff, #fafafa) !important;
    color: #6b7280 !important;
    padding: 14px 28px !important;
    border-radius: 5px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border: 2px solid #e9ecef !important;
    transition: all 0.3s !important;
}

.swal2-cancel-custom-delete:hover {
    background: linear-gradient(135deg, #f9fafb, #f3f4f6) !important;
    border-color: #d1d5db !important;
    transform: translateY(-2px) !important;
    color: #4b5563 !important;
}

.swal2-icon-custom-delete.swal2-warning {
    border-color: #dc2626 !important;
    color: #dc2626 !important;
    border-width: 4px !important;
}
</style>

/* Enhanced Delete Confirmation Styling */
.swal2-popup-custom-delete {
    border-radius: 5px !important;
    padding: 35px !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
    border: 1px solid #e9ecef !important;
    max-width: 550px !important;
}

.swal2-title-custom-delete {
    font-size: 28px !important;
    font-weight: 900 !important;
    color: #1a1a1a !important;
    margin-bottom: 20px !important;
    letter-spacing: -0.5px !important;
}

.swal2-html-container-custom-delete {
    font-size: 14px !important;
    color: #6b7280 !important;
    line-height: 1.6 !important;
    text-align: left !important;
}

.swal2-confirm-custom-delete {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    color: #fff !important;
    padding: 14px 28px !important;
    border-radius: 5px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border: none !important;
    transition: all 0.3s !important;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3) !important;
}

.swal2-confirm-custom-delete:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4) !important;
    background: linear-gradient(135deg, #b91c1c, #dc2626) !important;
}

.swal2-cancel-custom-delete {
    background: linear-gradient(135deg, #fff, #fafafa) !important;
    color: #6b7280 !important;
    padding: 14px 28px !important;
    border-radius: 5px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border: 2px solid #e9ecef !important;
    transition: all 0.3s !important;
}

.swal2-cancel-custom-delete:hover {
    background: linear-gradient(135deg, #f9fafb, #f3f4f6) !important;
    border-color: #d1d5db !important;
    transform: translateY(-2px) !important;
    color: #4b5563 !important;
}

.swal2-icon-custom-delete.swal2-warning {
    border-color: #dc2626 !important;
    color: #dc2626 !important;
    border-width: 4px !important;
}
</style>

<script>
// Handle Approve Button
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const commenter = this.getAttribute('data-commenter');
            
            Swal.fire({
                title: 'Setujui Komentar?',
                html: `<div style="text-align: left; padding: 10px 0;">
                        <p style="margin-bottom: 10px;"><strong>Komentar dari:</strong> ${commenter}</p>
                        <p style="color: #6b7280; font-size: 14px;">Komentar ini akan ditampilkan di halaman blog setelah disetujui.</p>
                      </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check me-2"></i>Ya, Setujui',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-popup-custom',
                    confirmButton: 'swal2-confirm-custom',
                    cancelButton: 'swal2-cancel-custom'
                }
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
                    
                    // Send AJAX request
                    fetch(`/admin/comments/${commentId}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Komentar berhasil disetujui!',
                            confirmButtonColor: '#dc2626',
                            timer: 3000,
                            timerProgressBar: true
                        });
                        
                        // Update UI without reload
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyetujui komentar.',
                            confirmButtonColor: '#dc2626'
                        });
                    });
                }
            });
        });
    });
    
    // Handle Reject Button
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const commenter = this.getAttribute('data-commenter');
            
            Swal.fire({
                title: 'Tolak Komentar?',
                html: `<div style="text-align: left; padding: 10px 0;">
                        <p style="margin-bottom: 10px;"><strong>Komentar dari:</strong> ${commenter}</p>
                        <p style="color: #6b7280; font-size: 14px;">Komentar ini akan ditandai sebagai ditolak dan tidak akan ditampilkan di halaman blog.</p>
                      </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-times me-2"></i>Ya, Tolak',
                cancelButtonText: '<i class="fas fa-arrow-left me-2"></i>Batal',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
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
                    
                    // Send AJAX request
                    fetch(`/admin/comments/${commentId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Berhasil!',
                            text: 'Komentar berhasil ditolak!',
                            confirmButtonColor: '#dc2626',
                            timer: 3000,
                            timerProgressBar: true
                        });
                        
                        // Update UI without reload
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menolak komentar.',
                            confirmButtonColor: '#dc2626'
                        });
                    });
                }
            });
        });
    });
    
    // Handle Delete Button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const commenter = this.getAttribute('data-commenter');
            const commentCard = document.querySelector(`.comment-card[data-comment-id="${commentId}"]`);
            const commentText = commentCard ? commentCard.querySelector('.comment-content').textContent.trim() : '';
            const commentPreview = commentText.length > 100 ? commentText.substring(0, 100) + '...' : commentText;
            
            Swal.fire({
                title: '<strong style="color: #1a1a1a;">Hapus Komentar?</strong>',
                html: `<div style="text-align: left; padding: 10px 0;">
                    <p style="color: #6b7280; margin-bottom: 15px;">Anda akan menghapus komentar berikut:</p>
                    <div style="background: #f9fafb; padding: 15px; border-radius: 5px; border-left: 4px solid #dc2626; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #dc2626, #991b1b); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 16px;">
                                ${commenter.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <strong style="color: #1a1a1a; display: block; margin-bottom: 3px;">${commenter}</strong>
                                <span style="color: #9ca3af; font-size: 12px;">Komentar</span>
                            </div>
                        </div>
                        <div style="background: #fff; padding: 12px; border-radius: 5px; border: 1px solid #e9ecef; margin-top: 10px;">
                            <p style="color: #4b5563; font-size: 14px; margin: 0; line-height: 1.5;">"${commentPreview}"</p>
                        </div>
                    </div>
                    <div style="background: #fef2f2; padding: 12px; border-radius: 5px; border-left: 4px solid #ef4444; margin-top: 10px;">
                        <p style="color: #dc2626; font-size: 14px; margin: 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Tindakan ini tidak dapat dibatalkan!</strong>
                        </p>
                        <p style="color: #991b1b; font-size: 13px; margin: 8px 0 0 0;">Komentar akan dihapus secara permanen dari database.</p>
                    </div>
                      </div>`,
                icon: 'warning',
                iconColor: '#dc2626',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus Komentar',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-popup-custom-delete',
                    confirmButton: 'swal2-confirm-custom-delete',
                    cancelButton: 'swal2-cancel-custom-delete',
                    title: 'swal2-title-custom-delete',
                    htmlContainer: 'swal2-html-container-custom-delete',
                    icon: 'swal2-icon-custom-delete'
                },
                buttonsStyling: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus Komentar...',
                        html: 'Mohon tunggu, komentar sedang dihapus.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'swal2-popup-custom-delete',
                            title: 'swal2-title-custom-delete'
                        }
                    });
                    
                    // Send AJAX request
                    fetch(`/admin/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Komentar berhasil dihapus!',
                            confirmButtonColor: '#dc2626',
                            timer: 3000,
                            timerProgressBar: true
                        });
                        
                        // Remove comment card with smooth animation
                        if (commentCard) {
                            commentCard.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                            commentCard.style.opacity = '0';
                            commentCard.style.transform = 'translateX(-100%) scale(0.95)';
                            commentCard.style.maxHeight = commentCard.offsetHeight + 'px';
                            setTimeout(() => {
                                commentCard.style.maxHeight = '0';
                                commentCard.style.marginBottom = '0';
                                commentCard.style.padding = '0';
                                setTimeout(() => {
                                    commentCard.remove();
                                    // Reload to update stats
                                    setTimeout(() => location.reload(), 500);
                                }, 400);
                            }, 400);
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: '<strong style="color: #1a1a1a;">Gagal!</strong>',
                            html: '<p style="color: #6b7280;">Terjadi kesalahan saat menghapus komentar. Silakan coba lagi.</p>',
                            confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                            confirmButtonColor: '#dc2626',
                            customClass: {
                                popup: 'swal2-popup-custom-delete',
                                confirmButton: 'swal2-confirm-custom-delete',
                                title: 'swal2-title-custom-delete'
                            },
                            buttonsStyling: false
                        });
                    });
                }
            });
        });
    });
});
</script>
@endpush

@endsection

