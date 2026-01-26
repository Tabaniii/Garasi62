<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 0;
        }
        .email-header {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1a1a1a;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .code-container {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 2px dashed #dc2626;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .code-label {
            font-size: 14px;
            color: #991b1b;
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .verification-code {
            font-size: 42px;
            font-weight: 900;
            color: #dc2626;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning-text {
            font-size: 14px;
            color: #92400e;
            margin: 0;
        }
        .footer {
            background-color: #1a1a1a;
            padding: 20px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }
        .footer a {
            color: #dc2626;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Garasi62</h1>
        </div>
        <div class="email-body">
            <div class="greeting">
                Halo, {{ $name }}!
            </div>
            <div class="message">
                Terima kasih telah mendaftar di Garasi62. Untuk menyelesaikan proses registrasi, silakan masukkan kode verifikasi berikut:
            </div>
            
            <div class="code-container">
                <div class="code-label">Kode Verifikasi</div>
                <div class="verification-code">{{ $code }}</div>
            </div>
            
            <div class="warning">
                <p class="warning-text">
                    <strong>⚠️ Penting:</strong> Kode ini berlaku selama 60 menit. Jangan bagikan kode ini kepada siapapun.
                </p>
            </div>
            
            <div class="message">
                Jika Anda tidak melakukan registrasi di Garasi62, abaikan email ini.
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Garasi62. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>

