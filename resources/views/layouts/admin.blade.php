<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - Garasi62</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #1a1a1a 0%, #000000 100%);
            color: #fff;
            padding: 0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            background: rgba(220, 38, 38, 0.1);
            border-bottom: 1px solid rgba(220, 38, 38, 0.3);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
            font-size: 22px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .sidebar-logo:hover {
            color: #dc2626;
        }

        .sidebar-logo-icon {
            width: 45px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .sidebar-user {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
        }

        .sidebar-user-info h6 {
            margin: 0;
            color: #fff;
            font-size: 14px;
        }

        .sidebar-user-info span {
            color: #dc2626;
            font-size: 12px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu-title {
            padding: 10px 20px;
            color: #999;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu-item:hover {
            background: rgba(220, 38, 38, 0.1);
            color: #fff;
            border-left-color: #dc2626;
        }

        .sidebar-menu-item.active {
            background: rgba(220, 38, 38, 0.2);
            color: #fff;
            border-left-color: #dc2626;
        }

        .sidebar-menu-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            height: 70px;
            background: #000000;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .header-home-btn {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }

        .header-home-btn:hover {
            background: linear-gradient(135deg, #b91c1c, #7f1d1d);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        }

        .header-home-btn:active {
            transform: translateY(0);
        }

        .header-toggle {
            background: #dc2626;
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .header-toggle:hover {
            background: #b91c1c;
        }

        .header-title {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icon {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 5px;
        }

        .header-icon:hover {
            background: rgba(220, 38, 38, 0.2);
            color: #fff;
        }

        .header-icon-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc2626;
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .header-user:hover {
            background: rgba(220, 38, 38, 0.2);
        }

        .header-user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        .page-title {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 32px;
            letter-spacing: -0.5px;
            position: relative;
            padding-bottom: 16px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #dc2626, #991b1b);
            border-radius: 5px;
        }

        /* Stat Cards */
        .stat-card {
            background: #fff;
            border-radius: 5px;
            padding: 28px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07), 0 1px 3px rgba(0,0,0,0.06);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.3), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0,0,0,0.12), 0 4px 8px rgba(0,0,0,0.08);
            border-color: rgba(220, 38, 38, 0.2);
        }

        .stat-card-icon {
            width: 70px;
            height: 70px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s;
        }

        .stat-card:hover .stat-card-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .stat-card-icon.red {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%);
        }

        .stat-card-icon.black {
            background: linear-gradient(135deg, #1a1a1a 0%, #0a0a0a 50%, #000000 100%);
        }

        .stat-card-icon.yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
        }

        .stat-card-icon.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
        }

        .stat-card-icon.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
        }

        .stat-card-icon.purple {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 50%, #7e22ce 100%);
        }

        .stat-card-value {
            font-size: 36px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .stat-card-label {
            font-size: 15px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Info Cards */
        .info-card {
            background: #fff;
            border-radius: 5px;
            padding: 28px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07), 0 1px 3px rgba(0,0,0,0.06);
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .info-card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.1), 0 2px 4px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }

        .info-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f5f5f5;
        }

        .info-card-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .info-card-link {
            color: #dc2626;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .info-card-link:hover {
            text-decoration: underline;
        }

        /* Chart Card */
        .chart-card {
            background: #fff;
            border-radius: 5px;
            padding: 28px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07), 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .chart-card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.1), 0 2px 4px rgba(0,0,0,0.06);
        }

        .chart-card-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 24px;
            letter-spacing: -0.3px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Alert */
        .alert-custom {
            border-radius: 5px;
            border-left: 4px solid #dc2626;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRow {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Animation Classes */
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-in-up {
            animation: slideInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-bounce-in {
            animation: bounceIn 0.8s ease-out forwards;
        }

        .animate-count-up {
            animation: countUp 1s ease-out forwards;
            animation-delay: 0.3s;
            opacity: 0;
        }

        .animate-fade-in-row {
            animation: fadeInRow 0.4s ease-out forwards;
            opacity: 0;
        }

        /* Button Animations */
        .btn-animate {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border-radius: 5px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .btn-animate::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-animate:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-animate:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.35);
        }

        .btn-animate:active {
            transform: translateY(-1px);
        }

        .btn-outline-danger.btn-animate {
            border-width: 2px;
            border-color: #dc2626;
        }

        .btn-outline-danger.btn-animate:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: #fff;
        }

        .btn-outline-dark.btn-animate {
            border-width: 2px;
            border-color: #1a1a1a;
        }

        .btn-outline-dark.btn-animate:hover {
            background: #1a1a1a;
            border-color: #1a1a1a;
            color: #fff;
        }

        /* Stat Card Hover Effects */
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card:hover .stat-card-icon {
            animation: pulse 1s ease-in-out infinite;
        }

        /* Table Row Animation */
        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
            box-shadow: -2px 0 5px rgba(220, 38, 38, 0.2);
        }

        /* Page Title Animation */
        .page-title {
            animation: fadeIn 0.8s ease-out;
        }

        /* Chart Card Animation */
        .chart-card {
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        /* Info Card Animation */
        .info-card {
            transition: all 0.3s ease;
        }

        .info-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <img src="{{ asset('img/logo.svg') }}" alt="Garasi62" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width: 100%; height: 100%; background: linear-gradient(135deg, #dc2626, #991b1b); border-radius: 5px; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 20px;">G</div>
                </div>
                <span>Garasi62</span>
            </a>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <h6>{{ Auth::user()->name }}</h6>
                <span>Online</span>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-menu-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            
            @if(Auth::user()->role === 'admin')
                {{-- Admin Menu --}}
                <a href="{{ route('cars.index') }}" class="sidebar-menu-item {{ request()->routeIs('cars.*') ? 'active' : '' }}">
                    <i class="fas fa-car"></i>
                    <span>List Mobil</span>
                </a>
                <a href="{{ route('users.sellers') }}" class="sidebar-menu-item {{ request()->routeIs('users.sellers') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>List Seller</span>
                </a>
                <a href="{{ route('admin.car-approvals.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.car-approvals.*') ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i>
                    <span>Persetujuan Mobil</span>
                    @php
                        $pendingCars = \App\Models\car::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCars > 0)
                    <span style="background: #dc2626; color: #fff; padding: 2px 8px; border-radius: 5px; font-size: 10px; margin-left: auto;">{{ $pendingCars }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.duplicate-cars.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.duplicate-cars.*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Deteksi Duplikat</span>
                </a>
                <a href="{{ route('blogs.admin.index') }}" class="sidebar-menu-item {{ request()->routeIs('blogs.admin.*') ? 'active' : '' }}">
                    <i class="fas fa-blog"></i>
                    <span>Blog</span>
                </a>
                <a href="{{ route('testimonials.admin.index') }}" class="sidebar-menu-item {{ request()->routeIs('testimonials.admin.*') ? 'active' : '' }}">
                    <i class="fas fa-quote-right"></i>
                    <span>Testimoni</span>
                </a>
                <a href="{{ route('comments.admin.index') }}" class="sidebar-menu-item {{ request()->routeIs('comments.admin.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Komentar</span>
                    @php
                        $pendingComments = \App\Models\Comment::where('status', 'pending')->count();
                    @endphp
                    @if($pendingComments > 0)
                    <span style="background: #dc2626; color: #fff; padding: 2px 8px; border-radius: 5px; font-size: 10px; margin-left: auto;">{{ $pendingComments }}</span>
                    @endif
                </a>
                <a href="{{ route('users.index') }}" class="sidebar-menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Pengguna</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
                    <span>Laporan Mobil</span>
                    @php
                        $pendingReports = \App\Models\Report::where('status', 'pending')->count();
                    @endphp
                    @if($pendingReports > 0)
                    <span style="background: #dc2626; color: #fff; padding: 2px 8px; border-radius: 5px; font-size: 10px; margin-left: auto;">{{ $pendingReports }}</span>
                    @endif
                </a>
                <a href="{{ route('about') }}" class="sidebar-menu-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Tentang</span>
                </a>
                <a href="{{ route('contact') }}" class="sidebar-menu-item">
                    <i class="fas fa-envelope"></i>
                    <span>Kontak</span>
                </a>
            @elseif(Auth::user()->role === 'seller')
                {{-- Seller Menu --}}
                <a href="{{ route('cars.index') }}" class="sidebar-menu-item {{ request()->routeIs('cars.*') ? 'active' : '' }}">
                    <i class="fas fa-car"></i>
                    <span>Mobil Saya</span>
                </a>
                <a href="{{ route('cars.create') }}" class="sidebar-menu-item {{ request()->routeIs('cars.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Mobil</span>
                </a>
                <a href="{{ route('chat.seller.index') }}" class="sidebar-menu-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Obrolan</span>
                    @php
                        $unreadChats = \App\Models\Chat::where('seller_id', Auth::id())
                            ->whereHas('messages', function($query) {
                                $query->where('sender_id', '!=', Auth::id())
                                      ->where('is_read', false);
                            })
                            ->count();
                    @endphp
                    @if($unreadChats > 0)
                    <span style="background: #dc2626; color: #fff; padding: 2px 8px; border-radius: 5px; font-size: 10px; margin-left: auto;">{{ $unreadChats }}</span>
                    @endif
                </a>
                <a href="{{ route('seller.reports.index') }}" class="sidebar-menu-item {{ request()->routeIs('seller.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
                    <span>Laporan Mobil</span>
                    @php
                        // Count reports that caused car to be unpublished (resolved with admin_notes)
                        $sellerUnpublishedReports = \App\Models\Report::where('seller_id', Auth::id())
                            ->where('status', 'resolved')
                            ->whereNotNull('admin_notes')
                            ->whereHas('car', function($query) {
                                $query->where('status', 'rejected');
                            })
                            ->count();
                    @endphp
                    @if($sellerUnpublishedReports > 0)
                    <span style="background: #dc2626; color: #fff; padding: 2px 8px; border-radius: 5px; font-size: 10px; margin-left: auto;">{{ $sellerUnpublishedReports }}</span>
                    @endif
                </a>
            @elseif(Auth::user()->role === 'buyer')
                {{-- Buyer Menu --}}
                <a href="{{ route('cars') }}" class="sidebar-menu-item">
                    <i class="fas fa-search"></i>
                    <span>Cari Mobil</span>
                </a>
                <a href="{{ route('chat.index') }}" class="sidebar-menu-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Obrolan</span>
                    @php
                        $unreadChats = \App\Models\Chat::where('buyer_id', Auth::id())
                            ->whereHas('messages', function($query) {
                                $query->where('sender_id', '!=', Auth::id())
                                      ->where('is_read', false);
                            })
                            ->count();
                    @endphp
                    @if($unreadChats > 0)
                    <span style="background: #dc2626; color: #fff; padding: 2px 8px; border-radius: 5px; font-size: 10px; margin-left: auto;">{{ $unreadChats }}</span>
                    @endif
                </a>
            @endif
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-menu-title">Pengaturan</div>
            <a href="#" class="sidebar-menu-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline" id="logout-form">
                @csrf
                <button type="button" class="sidebar-menu-item w-100 text-start border-0 bg-transparent logout-btn" style="color: #ccc;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <button class="header-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">@yield('header-title', 'Dashboard')</h1>
        </div>
        <div class="header-right">
            <a href="{{ route('index') }}" class="header-home-btn">
                <i class="fas fa-home me-2"></i>
                <span>Halaman Utama</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Logout confirmation with SweetAlert
        (function() {
            function initLogoutButton() {
                // Check if SweetAlert is loaded
                if (typeof Swal === 'undefined') {
                    setTimeout(initLogoutButton, 100);
                    return;
                }
                
                const logoutBtn = document.querySelector('.logout-btn');
                const logoutForm = document.getElementById('logout-form');
                
                if (!logoutBtn || !logoutForm) {
                    return;
                }
                
                // Check if already has listener
                if (logoutBtn.hasAttribute('data-listener-attached')) {
                    return;
                }
                
                logoutBtn.setAttribute('data-listener-attached', 'true');
                
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (typeof Swal === 'undefined') {
                        console.error('SweetAlert not loaded');
                        logoutForm.submit();
                        return;
                    }
                    
                    Swal.fire({
                        title: 'Apakah yakin ingin keluar?',
                        html: `<div style="text-align: center; padding: 10px 0;">
                                <p style="margin-bottom: 10px;">Anda akan keluar dari sistem</p>
                                <p style="color: #6b7280; font-size: 14px;">Pastikan semua pekerjaan Anda sudah disimpan</p>
                              </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-sign-out-alt me-2"></i>Ya, Keluar',
                        cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Keluar...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            // Submit logout form
                            logoutForm.submit();
                        }
                    });
                });
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(initLogoutButton, 200);
                });
            } else {
                setTimeout(initLogoutButton, 200);
            }
        })();

        // SweetAlert untuk session messages
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#dc2626',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc2626',
            showConfirmButton: true
        });
        @endif

        @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#dc2626',
            showConfirmButton: true
        });
        @endif

        @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '{{ session('info') }}',
            confirmButtonColor: '#dc2626',
            showConfirmButton: true
        });
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>
