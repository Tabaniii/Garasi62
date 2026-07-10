@extends('layouts.admin')

@section('header-title', 'Detail Laporan')

@section('content')
    <div class="report-detail-wrapper">
        <!-- Page Header -->
        <div class="page-header-report">
            <div class="page-header-content">
                <div>
                    <h1 class="page-title-report">Detail Laporan</h1>
                    <p class="page-subtitle-report">ID: #{{ $report->id }}</p>
                </div>
                <div class="status-badge-main 
                    @if($report->status == 'pending') status-pending-main
                    @elseif($report->status == 'reviewed') status-reviewed-main
                    @elseif($report->status == 'resolved') status-resolved-main
                    @else status-dismissed-main
                    @endif">
                    <i class="fas fa-circle"></i>
                    {{ $report->status_label }}
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column - Main Content -->
            <div class="col-lg-8">
                <!-- Report Information -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-left">
                            <div class="card-icon-wrapper">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div>
                                <h3 class="card-title-modern">Informasi Laporan</h3>
                                <p class="card-subtitle-modern">Detail lengkap laporan yang diterima</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body-modern">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Tanggal Laporan</span>
                                </div>
                                <div class="info-value">{{ $report->created_at->format('d M Y, H:i') }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Alasan</span>
                                </div>
                                <div class="info-value">
                                    <span class="badge-modern badge-reason-modern">{{ $report->reason_label }}</span>
                                </div>
                            </div>

                            @if($report->reviewer)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-user-check"></i>
                                        <span>Ditinjau Oleh</span>
                                    </div>
                                    <div class="info-value">
                                        {{ $report->reviewer->name }}
                                        <small class="text-muted">({{ $report->reviewed_at->format('d M Y, H:i') }})</small>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="message-section">
                            <div class="message-label">
                                <i class="fas fa-comment-alt"></i>
                                <span>Detail Laporan</span>
                            </div>
                            <div class="message-box">
                                {{ $report->message }}
                            </div>
                        </div>

                        @if($report->admin_notes)
                            <div class="admin-notes-section">
                                <div class="admin-notes-label">
                                    <i class="fas fa-sticky-note"></i>
                                    <span>Catatan Admin</span>
                                </div>
                                <div class="admin-notes-box">
                                    {{ $report->admin_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Car Information -->
                @if($report->car)
                    <div class="card-modern mt-4">
                        <div class="card-header-modern">
                            <div class="card-header-left">
                                <div class="card-icon-wrapper car-icon">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div>
                                    <h3 class="card-title-modern">Mobil yang Dilaporkan</h3>
                                    <p class="card-subtitle-modern">Informasi mobil terkait laporan</p>
                                </div>
                            </div>
                            <a href="{{ route('car.details', $report->car->uuid) }}" target="_blank" class="btn-view-link">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Lihat Detail</span>
                            </a>
                        </div>
                        <div class="card-body-modern">
                            <div class="car-preview">
                                <div class="car-image-wrapper">
                                    @if($report->car->image && is_array($report->car->image) && count($report->car->image) > 0)
                                        <img src="{{ asset('storage/' . $report->car->image[0]) }}" alt="{{ $report->car->brand }}"
                                            class="car-image-modern">
                                    @else
                                        <div class="car-placeholder-modern">
                                            <i class="fas fa-car"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="car-info-modern">
                                    <h4 class="car-name-modern">{{ strtoupper($report->car->brand) }} {{ $report->car->nama }}
                                    </h4>
                                    <div class="car-specs-modern">
                                        <div class="spec-item">
                                            <i class="fas fa-calendar"></i>
                                            <span>Tahun: <strong>{{ $report->car->tahun }}</strong></span>
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <span>Km:
                                                <strong>{{ number_format($report->car->kilometer, 0, ',', '.') }}</strong></span>
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-tag"></i>
                                            <span>Rp
                                                <strong>{{ number_format($report->car->harga, 0, ',', '.') }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="car-status-wrapper">
                                        <span class="badge-modern 
                                            @if($report->car->status == 'approved') badge-success-modern
                                            @elseif($report->car->status == 'pending') badge-warning-modern
                                            @else badge-danger-modern
                                            @endif">
                                            <i class="fas fa-circle"></i>
                                            {{ $report->car->status == 'approved' ? 'Disetujui' : ($report->car->status == 'pending' ? 'Menunggu' : 'Ditolak') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
                <!-- Reporter Card -->
                <div class="card-modern user-card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-left">
                            <div class="card-icon-wrapper user-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h3 class="card-title-modern">Pelapor</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body-modern">
                        @if($report->reporter)
                            <div class="user-profile-modern">
                                <div class="user-avatar-modern">
                                    {{ strtoupper(substr($report->reporter->name, 0, 1)) }}
                                </div>
                                <div class="user-info-modern">
                                    <h5 class="user-name-modern">{{ $report->reporter->name }}</h5>
                                    <div class="user-contact-modern">
                                        <div class="contact-item">
                                            <i class="fas fa-envelope"></i>
                                            <span>{{ $report->reporter->email }}</span>
                                        </div>
                                        @if($report->reporter->phone)
                                            <div class="contact-item">
                                                <i class="fas fa-phone"></i>
                                                <span>{{ $report->reporter->phone }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="empty-state-modern">
                                <i class="fas fa-user-slash"></i>
                                <p>Informasi pelapor tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Seller Card -->
                <div class="card-modern user-card-modern mt-4">
                    <div class="card-header-modern">
                        <div class="card-header-left">
                            <div class="card-icon-wrapper seller-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h3 class="card-title-modern">Seller</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body-modern">
                        @if($report->seller)
                            <div class="user-profile-modern">
                                <div class="user-avatar-modern seller-avatar-modern">
                                    {{ strtoupper(substr($report->seller->name, 0, 1)) }}
                                </div>
                                <div class="user-info-modern">
                                    <h5 class="user-name-modern">{{ $report->seller->name }}</h5>
                                    <div class="user-contact-modern">
                                        <div class="contact-item">
                                            <i class="fas fa-envelope"></i>
                                            <span>{{ $report->seller->email }}</span>
                                        </div>
                                        @if($report->seller->phone)
                                            <div class="contact-item">
                                                <i class="fas fa-phone"></i>
                                                <span>{{ $report->seller->phone }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="empty-state-modern">
                                <i class="fas fa-user-slash"></i>
                                <p>Informasi seller tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Card -->
                <div class="card-modern action-card-modern mt-4">
                    <div class="card-header-modern">
                        <div class="card-header-left">
                            <div class="card-icon-wrapper action-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div>
                                <h3 class="card-title-modern">Aksi</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body-modern">
                        <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="action-form">
                            @csrf
                            @method('PUT')
                            <div class="form-group-modern">
                                <label for="status" class="form-label-modern">
                                    <i class="fas fa-info-circle"></i>
                                    Status Laporan
                                </label>
                                <select class="form-control-modern" id="status" name="status" required>
                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Menunggu
                                        Review</option>
                                    <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>Sedang
                                        Ditinjau</option>
                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="dismissed" {{ $report->status == 'dismissed' ? 'selected' : '' }}>Ditolak
                                    </option>
                                </select>
                            </div>

                            <div class="form-group-modern">
                                <label for="admin_notes" class="form-label-modern">
                                    <i class="fas fa-sticky-note"></i>
                                    Catatan Admin
                                </label>
                                <textarea class="form-control-modern" id="admin_notes" name="admin_notes" rows="3"
                                    placeholder="Tambahkan catatan untuk laporan ini...">{{ $report->admin_notes }}</textarea>
                            </div>

                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-save"></i>
                                <span>Update Status</span>
                            </button>
                        </form>

                        @if($report->car && $report->car->status == 'approved')
                            <div class="divider-modern">
                                <span>Aksi Cepat</span>
                            </div>
                            <form action="{{ route('admin.reports.unpublish-car', $report) }}" method="POST"
                                id="unpublish-car-form">
                                @csrf
                                <div class="form-group-modern">
                                    <label for="unpublish_notes" class="form-label-modern">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Alasan Unpublish
                                    </label>
                                    <textarea class="form-control-modern" id="unpublish_notes" name="admin_notes" rows="2"
                                        placeholder="Masukkan alasan mengapa mobil di-unpublish..."></textarea>
                                </div>
                                <button type="button" class="btn-modern btn-warning-modern" onclick="confirmUnpublish()">
                                    <i class="fas fa-ban"></i>
                                    <span>Unpublish Mobil</span>
                                </button>
                            </form>
                        @endif

                        <div class="back-link-modern">
                            <a href="{{ route('admin.reports.index') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-arrow-left"></i>
                                <span>Kembali ke Daftar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Main Container */
        .report-detail-wrapper {
            padding: 0;
        }

        /* Page Header */
        .page-header-report {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 24px 28px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #e9ecef;
        }

        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title-report {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }

        .page-subtitle-report {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .status-badge-main {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge-main i {
            font-size: 8px;
        }

        .status-pending-main {
            background: #fef3c7;
            color: #92400e;
        }

        .status-reviewed-main {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-resolved-main {
            background: #d1fae5;
            color: #065f46;
        }

        .status-dismissed-main {
            background: #f3f4f6;
            color: #4b5563;
        }

        /* Modern Card */
        .card-modern {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-modern:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .card-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #e9ecef;
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .card-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            background: linear-gradient(135deg, #dc2626, #ef4444);
        }

        .card-icon-wrapper.car-icon {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }

        .card-icon-wrapper.user-icon {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .card-icon-wrapper.seller-icon {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .card-icon-wrapper.action-icon {
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
        }

        .card-title-modern {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 4px 0;
        }

        .card-subtitle-modern {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }

        .card-body-modern {
            padding: 24px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-label i {
            color: #9ca3af;
            font-size: 14px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .badge-modern {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-modern i {
            font-size: 6px;
        }

        .badge-reason-modern {
            background: #3b82f6;
            color: #fff;
        }

        .badge-success-modern {
            background: #10b981;
            color: #fff;
        }

        .badge-warning-modern {
            background: #f59e0b;
            color: #fff;
        }

        .badge-danger-modern {
            background: #dc2626;
            color: #fff;
        }

        /* Message Section */
        .message-section,
        .admin-notes-section {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e9ecef;
        }

        .message-label,
        .admin-notes-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .message-label i,
        .admin-notes-label i {
            color: #9ca3af;
            font-size: 14px;
        }

        .message-box {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
            color: #4b5563;
            line-height: 1.6;
            white-space: pre-wrap;
            font-size: 14px;
        }

        .admin-notes-box {
            background: #fef3c7;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
            color: #4b5563;
            line-height: 1.6;
            white-space: pre-wrap;
            font-size: 14px;
        }

        /* Car Preview */
        .car-preview {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .car-image-wrapper {
            width: 180px;
            height: 135px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .car-image-modern {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .car-placeholder-modern {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
        }

        .car-placeholder-modern i {
            font-size: 48px;
        }

        .car-info-modern {
            flex: 1;
        }

        .car-name-modern {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 16px 0;
            letter-spacing: -0.5px;
        }

        .car-specs-modern {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
        }

        .spec-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #6b7280;
        }

        .spec-item i {
            width: 18px;
            color: #9ca3af;
        }

        .spec-item strong {
            color: #1a1a1a;
            font-weight: 700;
        }

        .car-status-wrapper {
            margin-top: 12px;
        }

        .btn-view-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: #fff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-view-link:hover {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        /* User Profile Modern */
        .user-card-modern {
            margin-bottom: 0;
        }

        .user-profile-modern {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .user-avatar-modern {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        }

        .seller-avatar-modern {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .user-info-modern {
            flex: 1;
            min-width: 0;
        }

        .user-name-modern {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 12px 0;
        }

        .user-contact-modern {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #6b7280;
        }

        .contact-item i {
            width: 16px;
            color: #9ca3af;
        }

        .empty-state-modern {
            text-align: center;
            padding: 32px 16px;
            color: #9ca3af;
        }

        .empty-state-modern i {
            font-size: 32px;
            margin-bottom: 12px;
            display: block;
        }

        .empty-state-modern p {
            margin: 0;
            font-size: 14px;
        }

        /* Form Modern */
        .action-form {
            margin-bottom: 0;
        }

        .form-group-modern {
            margin-bottom: 20px;
        }

        .form-label-modern {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-label-modern i {
            color: #9ca3af;
            font-size: 14px;
        }

        .form-control-modern {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background: #fff;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-control-modern::placeholder {
            color: #9ca3af;
        }

        /* Button Modern */
        .btn-modern {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: #fff;
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }

        .btn-warning-modern:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-secondary-modern {
            background: #fff;
            color: #6b7280;
            border: 1.5px solid #d1d5db;
        }

        .btn-secondary-modern:hover {
            background: #f9fafb;
            border-color: #9ca3af;
            color: #4b5563;
        }

        .divider-modern {
            position: relative;
            text-align: center;
            margin: 24px 0;
            padding: 0 16px;
        }

        .divider-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
        }

        .divider-modern span {
            position: relative;
            background: #fff;
            padding: 0 12px;
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .back-link-modern {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .car-preview {
                flex-direction: column;
            }

            .car-image-wrapper {
                width: 100%;
                height: 200px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .page-header-report {
                padding: 20px;
            }

            .page-title-report {
                font-size: 24px;
            }

            .card-header-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .card-body-modern {
                padding: 20px;
            }

            .user-profile-modern {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmUnpublish() {
                const form = document.getElementById('unpublish-car-form');
                const carName = '{{ $report->car ? strtoupper($report->car->brand) . " " . $report->car->nama : "Mobil ini" }}';
                const reason = document.getElementById('unpublish_notes').value || 'Tidak ada alasan yang diberikan';

                Swal.fire({
                    title: '<strong style="color: #1a1a1a;">Unpublish Mobil?</strong>',
                    html: `<div style="text-align: left; padding: 5px 0;">
                    <p style="color: #6b7280; margin-bottom: 10px; font-size: 13px;">Anda akan meng-unpublish mobil berikut:</p>
                    <div style="background: #f9fafb; padding: 12px; border-radius: 5px; border-left: 3px solid #f59e0b; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; border-radius: 5px; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; flex-shrink: 0;">
                                <i class="fas fa-car"></i>
                            </div>
                            <div style="flex: 1;">
                                <strong style="color: #1a1a1a; display: block; margin-bottom: 3px; font-size: 14px;">${carName}</strong>
                            </div>
                        </div>
                    </div>
                    <div style="background: #fef3c7; padding: 10px; border-radius: 5px; border-left: 3px solid #f59e0b; margin-top: 10px;">
                        <p style="color: #92400e; font-size: 12px; margin: 0; display: flex; align-items: center; gap: 6px; margin-bottom: 5px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 12px;"></i>
                            <strong>Mobil akan disembunyikan dari publik!</strong>
                        </p>
                        <p style="color: #78350f; font-size: 11px; margin: 0;">
                            Mobil tidak akan muncul di halaman pencarian dan detail mobil.
                        </p>
                    </div>
                </div>`,
                    icon: 'warning',
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-ban me-2"></i>Ya, Unpublish Mobil',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-popup-custom-unpublish',
                        confirmButton: 'swal2-confirm-custom-unpublish',
                        cancelButton: 'swal2-cancel-custom-unpublish',
                    },
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Meng-unpublish Mobil...',
                            html: '<p style="font-size: 13px; color: #6b7280;">Mohon tunggu, mobil sedang di-unpublish.</p>',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        form.submit();
                    }
                });
            }
        </script>
        <style>
            .swal2-popup-custom-unpublish {
                border-radius: 5px !important;
                padding: 20px !important;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
                border: 1px solid #e9ecef !important;
                max-width: 450px !important;
            }

            .swal2-confirm-custom-unpublish {
                background: linear-gradient(135deg, #f59e0b, #d97706) !important;
                color: #fff !important;
                padding: 10px 20px !important;
                border-radius: 5px !important;
                font-weight: 700 !important;
                font-size: 13px !important;
                border: none !important;
                transition: all 0.3s !important;
                box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3) !important;
            }

            .swal2-confirm-custom-unpublish:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4) !important;
                background: linear-gradient(135deg, #d97706, #b45309) !important;
            }

            .swal2-cancel-custom-unpublish {
                background: linear-gradient(135deg, #fff, #fafafa) !important;
                color: #6b7280 !important;
                padding: 10px 20px !important;
                border-radius: 5px !important;
                font-weight: 700 !important;
                font-size: 13px !important;
                border: 2px solid #e9ecef !important;
                transition: all 0.3s !important;
            }

            .swal2-cancel-custom-unpublish:hover {
                background: linear-gradient(135deg, #f9fafb, #f3f4f6) !important;
                border-color: #d1d5db !important;
                transform: translateY(-2px) !important;
                color: #4b5563 !important;
            }
        </style>
    @endpush
@endsection