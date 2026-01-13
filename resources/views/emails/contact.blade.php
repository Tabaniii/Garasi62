<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form - Garasi62</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #dc3545; color: #ffffff; padding: 30px 20px; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Pesan Baru dari Contact Form</h1>
                            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Garasi62</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding-bottom: 15px;">
                                        <strong style="color: #dc3545; font-size: 14px; display: block; margin-bottom: 5px;">Nama:</strong>
                                        <div style="background-color: #f9f9f9; padding: 12px; border-left: 3px solid #dc3545; border-radius: 4px; font-size: 14px; color: #333;">{{ $name }}</div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 15px;">
                                        <strong style="color: #dc3545; font-size: 14px; display: block; margin-bottom: 5px;">Email:</strong>
                                        <div style="background-color: #f9f9f9; padding: 12px; border-left: 3px solid #dc3545; border-radius: 4px; font-size: 14px; color: #333;">
                                            <a href="mailto:{{ $email }}" style="color: #dc3545; text-decoration: none;">{{ $email }}</a>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 15px;">
                                        <strong style="color: #dc3545; font-size: 14px; display: block; margin-bottom: 5px;">Subject:</strong>
                                        <div style="background-color: #f9f9f9; padding: 12px; border-left: 3px solid #dc3545; border-radius: 4px; font-size: 14px; color: #333;">{{ $subject }}</div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 15px;">
                                        <strong style="color: #dc3545; font-size: 14px; display: block; margin-bottom: 5px;">Pesan:</strong>
                                        <div style="background-color: #f9f9f9; padding: 15px; border-left: 3px solid #dc3545; border-radius: 4px; font-size: 14px; color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $messageContent }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #666666;">
                                Email ini dikirim dari contact form Garasi62
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #666666;">
                                Anda bisa membalas email ini langsung ke: 
                                <a href="mailto:{{ $email }}" style="color: #dc3545; text-decoration: none;">{{ $email }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

