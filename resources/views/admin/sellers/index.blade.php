@extends('layouts.admin')

@section('header-title', 'List Seller')

@section('content')
<div class="page-header-section mb-5">
    <div class="page-header-content">
        <div class="page-header-text">
            <div class="page-title-wrapper">
                <h1 class="page-title">List Seller</h1>
                @if($sellers->total() > 0)
                <span class="page-badge">{{ $sellers->total() }} Seller</span>
                @endif
            </div>
            <p class="page-subtitle">
                <i class="fas fa-info-circle me-2"></i>Daftar semua seller yang terdaftar di sistem
            </p>
        </div>
    </div>
</div>

@if($sellers->total() > 0)
<div class="row g-4">
    <div class="col-12">
        <div class="info-card animate-fade-in">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="fas fa-user-tie me-2"></i>Data Seller
                </h5>
                <span class="badge bg-danger">{{ $sellers->total() }} Total Seller</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover user-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Kota</th>
                            <th>Institusi</th>
                            <th>Total Mobil</th>
                            <th>Disetujui</th>
                            <th>Menunggu</th>
                            <th>Ditolak</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sellers as $index => $seller)
                        <tr>
                            <td>{{ $sellers->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($seller->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $seller->name }}</span>
                                </div>
                            </td>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->phone ?? '-' }}</td>
                            <td>{{ $seller->city ?? '-' }}</td>
                            <td>
                                @if($seller->institution)
                                    <span class="badge bg-info">{{ $seller->institution }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $seller->total_cars ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $seller->approved_cars ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $seller->pending_cars ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-danger">{{ $seller->rejected_cars ?? 0 }}</span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $seller->created_at->format('d M Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group-action">
                                    <a href="{{ route('users.edit', $seller->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('cars.index') }}?seller_id={{ $seller->id }}" class="btn-action btn-view" title="Lihat Mobil">
                                        <i class="fas fa-car"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-user-tie fa-3x mb-3"></i>
                                    <p>Belum ada seller</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sellers->hasPages())
            <div class="pagination-wrapper mt-4">
                {{ $sellers->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@else
<div class="empty-state animate-fade-in">
    <div class="empty-state-icon">
        <i class="fas fa-user-tie"></i>
    </div>
    <h4 class="empty-state-title">Belum ada seller</h4>
    <p class="empty-state-text">Seller yang terdaftar akan muncul di sini</p>
</div>
@endif

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
    border-radius: 5px !important;
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
    border-radius: 5px;
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
    border-radius: 5px;
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
    border-radius: 5px;
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
    border-radius: 5px;
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

.btn-group-action {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-action {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    font-size: 14px;
}

.btn-edit {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
}

.btn-edit:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    color: #fff;
}

.btn-view {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
}

.btn-view:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    color: #fff;
}

.empty-state {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border-radius: 5px !important;
    padding: 80px 40px !important;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06) !important;
    border: 2px dashed #e9ecef !important;
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 25px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.empty-state-icon i {
    font-size: 56px;
    color: #9ca3af;
}

.empty-state-title {
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #1a1a1a !important;
    margin-bottom: 12px !important;
}

.empty-state-text {
    font-size: 16px !important;
    color: #6b7280 !important;
    margin-bottom: 30px !important;
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

