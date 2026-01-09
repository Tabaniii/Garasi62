@extends('layouts.admin')

@section('header-title', 'Kelola Pengguna')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="page-header-section mb-5">
    <div class="page-header-content">
        <div class="page-header-text">
            <div class="page-title-wrapper">
                <h1 class="page-title">Kelola Pengguna</h1>
                <span class="page-badge">{{ $users->total() }} Pengguna</span>
            </div>
            <p class="page-subtitle">
                <i class="fas fa-info-circle me-2"></i>Daftar semua pengguna yang terdaftar di sistem
            </p>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-users me-2"></i>Data Pengguna
                </h5>
                <span class="badge bg-danger">{{ $users->total() }} Total Pengguna</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover user-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Jenis Kelamin</th>
                            <th>Kota</th>
                            <th>Institusi</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                @if($user->gender)
                                    <span class="badge bg-info">{{ ucfirst($user->gender) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $user->city ?? '-' }}</td>
                            <td>{{ $user->institution ?? '-' }}</td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->created_at->format('d M Y') }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Belum ada data pengguna</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="pagination-wrapper mt-4">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-5 pt-4 border-top">
    <a href="{{ route('dashboard') }}" class="btn-back-dashboard">
        <i class="fas fa-arrow-left me-2"></i>
        <span>Kembali ke Dashboard</span>
    </a>
</div>

<style>
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
    padding: 8px 18px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 4px 16px rgba(220, 38, 38, 0.4), 0 0 0 0 rgba(220, 38, 38, 0.5);
    animation: pulse 2s ease-in-out infinite;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.page-subtitle {
    font-size: 15px;
    color: #6b7280;
    margin: 0;
    font-weight: 500;
}

.page-subtitle i {
    color: #dc2626;
    margin-right: 8px;
}

.user-table {
    margin-bottom: 0;
}

.user-table thead th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #1a1a1a;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 16px;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.user-table tbody tr {
    transition: all 0.3s;
    border-bottom: 1px solid #f3f4f6;
}

.user-table tbody tr:hover {
    background: linear-gradient(135deg, #fafafa, #f5f5f5);
    transform: translateX(4px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.user-table tbody td {
    padding: 16px;
    vertical-align: middle;
    font-size: 14px;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    margin-right: 12px;
    flex-shrink: 0;
}

.user-table .badge {
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 6px;
}

.info-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

.info-card-header .badge {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 700;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
}

.pagination-wrapper .pagination {
    gap: 8px;
}

.pagination-wrapper .page-link {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    color: #6b7280;
    padding: 10px 16px;
    font-weight: 600;
    transition: all 0.3s;
}

.pagination-wrapper .page-link:hover {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    border-color: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    border-color: #dc2626;
    color: #fff;
}

.btn-back-dashboard {
    display: inline-flex !important;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #1a1a1a, #374151);
    color: #fff;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    border: none;
}

.btn-back-dashboard:hover {
    background: linear-gradient(135deg, #000000, #1a1a1a);
    transform: translateX(-6px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    color: #fff;
}

@media (max-width: 768px) {
    .user-table {
        font-size: 12px;
    }
    
    .user-table thead th,
    .user-table tbody td {
        padding: 10px 8px;
    }
    
    .info-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}
</style>
@endsection

