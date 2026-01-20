<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobil Di-Unpublish - Garasi62</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #dc2626;
        }
        .header h1 {
            color: #dc2626;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .alert-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .alert-box h3 {
            margin-top: 0;
            color: #92400e;
        }
        .car-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .car-info h3 {
            margin-top: 0;
            color: #1a1a1a;
        }
        .reason-box {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .reason-box strong {
            color: #991b1b;
        }
        .report-detail-box {
            background: #ffffff;
            border: 2px solid #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .report-info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        .report-info-item:last-of-type {
            border-bottom: none;
        }
        .report-message-box {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e9ecef;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6b7280;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #dc2626;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🚗 Garasi62</h1>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $sellerName }}</strong>,</p>
            
            <p>Kami ingin memberitahu Anda bahwa mobil yang Anda posting telah di-unpublish dari platform Garasi62.</p>

            <div class="car-info">
                <h3>Informasi Mobil:</h3>
                <p><strong>Merek:</strong> {{ $carBrand }}</p>
                <p><strong>Model:</strong> {{ $carModel }}</p>
                <p><strong>Nama Lengkap:</strong> {{ $carName }}</p>
            </div>

            <div class="alert-box">
                <h3>⚠️ Status: Di-Unpublish</h3>
                <p>Mobil Anda telah di-unpublish dan tidak akan muncul di halaman pencarian atau detail mobil.</p>
            </div>

            <div class="reason-box">
                <h3>Alasan Unpublish:</h3>
                <p><strong>Laporan yang Diterima:</strong> {{ $reportReason }}</p>
                <p><strong>Keterangan Admin:</strong> {{ $reason }}</p>
            </div>

            <!-- Detail Laporan -->
            <div class="report-detail-box">
                <h3 style="margin-top: 0; color: #1a1a1a; font-size: 18px; margin-bottom: 15px;">
                    <i class="fas fa-file-alt" style="margin-right: 8px; color: #3b82f6;"></i>Detail Laporan
                </h3>
                
                <div class="report-info-item">
                    <strong style="color: #6b7280; display: block; margin-bottom: 5px;">ID Laporan:</strong>
                    <span style="color: #1a1a1a; font-weight: 600;">#{{ $reportId }}</span>
                </div>

                <div class="report-info-item">
                    <strong style="color: #6b7280; display: block; margin-bottom: 5px;">Tanggal Laporan:</strong>
                    <span style="color: #1a1a1a;">{{ $reportDate }}</span>
                </div>

                <div class="report-info-item">
                    <strong style="color: #6b7280; display: block; margin-bottom: 5px;">Dilaporkan oleh:</strong>
                    <span style="color: #1a1a1a;">{{ $reporterName }}</span>
                </div>

                <div class="report-info-item">
                    <strong style="color: #6b7280; display: block; margin-bottom: 5px;">Alasan Laporan:</strong>
                    <span style="background: #3b82f6; color: #fff; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block;">{{ $reportReason }}</span>
                </div>

                <div class="report-message-box">
                    <strong style="color: #6b7280; display: block; margin-bottom: 10px;">Isi Laporan:</strong>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #3b82f6; color: #4b5563; line-height: 1.6; white-space: pre-wrap;">{{ $reportMessage }}</div>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ $reportUrl }}" class="btn" style="background: #3b82f6;">Lihat Detail Laporan Lengkap</a>
                </div>
            </div>

            <p style="margin-top: 20px;">Jika Anda memiliki pertanyaan atau ingin mengajukan banding, silakan hubungi tim support kami melalui halaman kontak di website.</p>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ url('/contact') }}" class="btn">Hubungi Support</a>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah menggunakan layanan Garasi62.</p>
            <p>© {{ date('Y') }} Garasi62. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

