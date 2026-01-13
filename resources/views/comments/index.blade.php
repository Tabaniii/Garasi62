@extends('layouts.admin')

@section('header-title', 'Kelola Komentar')

@section('content')
<style>
.comment-card {
    background: #fff;
    border-radius: 12px;
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
    border-radius: 20px;
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
    border-radius: 8px;
    border-left: 3px solid #dc2626;
}

.comment-actions {
    display: flex;
    gap: 10px;
}

.btn-action-sm {
    padding: 8px 16px;
    border-radius: 8px;
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
    border-radius: 12px;
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
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

@if(session('success'))
<div id="success-notification" data-message="{{ session('success') }}" style="display: none;"></div>
@endif

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
.toast-notification {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: #fff;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
    margin-bottom: 15px;
    min-width: 350px;
    max-width: 450px;
    display: flex;
    align-items: center;
    gap: 15px;
    animation: slideInRight 0.4s ease-out, fadeOut 0.3s ease-in 4.7s forwards;
    position: relative;
    overflow: hidden;
}

.toast-notification::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    animation: progressBar 5s linear forwards;
}

.toast-notification.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    box-shadow: 0 10px 40px rgba(220, 38, 38, 0.3);
}

.toast-notification.warning {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3);
}

.toast-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 700;
    font-size: 16px;
    margin-bottom: 5px;
}

.toast-message {
    font-size: 14px;
    opacity: 0.95;
    line-height: 1.4;
}

.toast-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #fff;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    flex-shrink: 0;
}

.toast-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

@keyframes progressBar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

/* SweetAlert2 Custom Styling */
.swal2-popup {
    border-radius: 16px !important;
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
    border-radius: 10px !important;
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
    border-radius: 10px !important;
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
</style>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    
    const icons = {
        success: '<i class="fas fa-check-circle"></i>',
        error: '<i class="fas fa-times-circle"></i>',
        warning: '<i class="fas fa-exclamation-triangle"></i>'
    };
    
    const titles = {
        success: 'Berhasil!',
        error: 'Gagal!',
        warning: 'Peringatan!'
    };
    
    toast.innerHTML = `
        <div class="toast-icon">${icons[type] || icons.success}</div>
        <div class="toast-content">
            <div class="toast-title">${titles[type] || titles.success}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'fadeOut 0.3s ease-in forwards';
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);
}

// Show notification if there's a success message
document.addEventListener('DOMContentLoaded', function() {
    const successNotification = document.getElementById('success-notification');
    if (successNotification) {
        const message = successNotification.getAttribute('data-message');
        showToast(message, 'success');
    }
    
    // Handle Approve Button
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
                        showToast('Komentar berhasil disetujui!', 'success');
                        
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
                        showToast('Komentar berhasil ditolak!', 'error');
                        
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
            
            Swal.fire({
                title: 'Hapus Komentar?',
                html: `<div style="text-align: left; padding: 10px 0;">
                        <p style="margin-bottom: 10px;"><strong>Komentar dari:</strong> ${commenter}</p>
                        <p style="color: #ef4444; font-size: 14px; font-weight: 600;">⚠️ Tindakan ini tidak dapat dibatalkan!</p>
                        <p style="color: #6b7280; font-size: 14px;">Komentar akan dihapus secara permanen dari database.</p>
                      </div>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus',
                cancelButtonText: '<i class="fas fa-arrow-left me-2"></i>Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
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
                        showToast('Komentar berhasil dihapus!', 'error');
                        
                        // Remove comment card with animation
                        const commentCard = document.querySelector(`.comment-card[data-comment-id="${commentId}"]`);
                        if (commentCard) {
                            commentCard.style.transition = 'all 0.3s ease-out';
                            commentCard.style.opacity = '0';
                            commentCard.style.transform = 'translateX(-100%)';
                            commentCard.style.maxHeight = commentCard.offsetHeight + 'px';
                            setTimeout(() => {
                                commentCard.style.maxHeight = '0';
                                commentCard.style.marginBottom = '0';
                                commentCard.style.padding = '0';
                                setTimeout(() => {
                                    commentCard.remove();
                                    // Reload to update stats
                                    setTimeout(() => location.reload(), 500);
                                }, 300);
                            }, 300);
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus komentar.',
                            confirmButtonColor: '#dc2626'
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

