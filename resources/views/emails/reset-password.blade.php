<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Garasi62</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 5px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #dc3545; color: #ffffff; padding: 30px 20px; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Reset Password</h1>
                            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Garasi62</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #333; line-height: 1.6;">
                                Halo,
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #333; line-height: 1.6;">
                                Kami menerima permintaan untuk mereset password akun Anda di Garasi62. Jika Anda yang melakukan permintaan ini, silakan klik tombol di bawah ini untuk mereset password Anda.
                            </p>
                            
                            <!-- Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" style="display: inline-block; padding: 14px 30px; background-color: #dc3545; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 600; font-size: 16px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #666; line-height: 1.6;">
                                Atau salin dan tempel link berikut ke browser Anda:
                            </p>
                            <p style="margin: 10px 0 20px 0; font-size: 12px; color: #999; word-break: break-all; background-color: #f9f9f9; padding: 10px; border-radius: 5px;">
                                {{ $url }}
                            </p>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #666; line-height: 1.6;">
                                <strong>Penting:</strong> Link ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak melakukan permintaan reset password, abaikan email ini.
                            </p>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #666; line-height: 1.6;">
                                Jika tombol tidak berfungsi, silakan salin link di atas dan buka di browser Anda.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #666666;">
                                Email ini dikirim dari sistem Garasi62
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #666666;">
                                Jangan balas email ini. Jika Anda memiliki pertanyaan, silakan hubungi tim support kami.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

