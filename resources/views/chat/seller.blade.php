@extends('template.temp')

@section('content')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option set-bg" data-setbg="{{ asset('garasi62/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Chat ke Penjual</h2>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                            <span>Chat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Chat Section Begin -->
    <section class="chat-section spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="chat-card">
                        <div class="chat-header">
                            <h3><i class="fa fa-user"></i> Informasi Penjual</h3>
                        </div>
                        <div class="chat-body">
                            <div class="seller-info">
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-user"></i> Nama:</span>
                                    <span class="info-value">{{ $seller->name }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-envelope"></i> Email:</span>
                                    <span class="info-value">
                                        <a href="mailto:{{ $seller->email }}">{{ $seller->email }}</a>
                                    </span>
                                </div>
                                @if($seller->phone)
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-phone"></i> Phone:</span>
                                    <span class="info-value">
                                        <a href="tel:{{ $seller->phone }}">{{ $seller->phone }}</a>
                                    </span>
                                </div>
                                @endif
                                @if($car)
                                <div class="info-item">
                                    <span class="info-label"><i class="fa fa-car"></i> Mobil:</span>
                                    <span class="info-value">{{ $car->brand }} {{ $car->nama ?? '' }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="chat-actions">
                                @if($seller->phone)
                                <a href="tel:{{ $seller->phone }}" class="btn-chat-action btn-phone">
                                    <i class="fa fa-phone"></i> Hubungi via Telepon
                                </a>
                                @endif
                                <a href="mailto:{{ $seller->email }}" class="btn-chat-action btn-email">
                                    <i class="fa fa-envelope"></i> Kirim Email
                                </a>
                            </div>
                            <div class="chat-note">
                                <p><i class="fa fa-info-circle"></i> <strong>Catatan:</strong> Penjual tidak memiliki nomor WhatsApp. Silakan hubungi melalui email atau telepon.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Chat Section End -->

    <style>
        .chat-section {
            padding: 60px 0;
        }

        .chat-card {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .chat-header {
            background: linear-gradient(135deg, #df2d24, #b91c1c);
            color: #fff;
            padding: 24px 30px;
        }

        .chat-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-body {
            padding: 30px;
        }

        .seller-info {
            margin-bottom: 24px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            background: linear-gradient(135deg, #fafbfc, #ffffff);
            border-radius: 5px;
            margin-bottom: 12px;
            border: 1px solid #f0f0f0;
        }

        .info-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 700;
        }

        .info-value a {
            color: #df2d24;
            text-decoration: none;
            transition: all 0.3s;
        }

        .info-value a:hover {
            color: #b91c1c;
            text-decoration: underline;
        }

        .chat-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .btn-chat-action {
            flex: 1;
            padding: 14px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #fff;
        }

        .btn-phone {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-phone:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: #fff;
        }

        .btn-email {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-email:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: #fff;
        }

        .chat-note {
            padding: 16px;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 5px;
            border-left: 4px solid #f59e0b;
        }

        .chat-note p {
            margin: 0;
            font-size: 13px;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 767px) {
            .chat-actions {
                flex-direction: column;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
@endsection

