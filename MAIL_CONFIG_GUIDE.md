# Panduan Konfigurasi Mail untuk Contact Form

## Masalah yang Sering Terjadi

Error "Terjadi kesalahan saat mengirim pesan" biasanya terjadi karena konfigurasi mail di file `.env` belum diatur dengan benar.

## Solusi 1: Menggunakan Log Mailer (Paling Mudah - Untuk Development)

Jika Anda hanya ingin testing dan melihat email di log file, gunakan konfigurasi ini di `.env`:

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=info@garasi62.co.id
MAIL_FROM_NAME="${APP_NAME}"
```

Dengan konfigurasi ini, email akan disimpan di `storage/logs/laravel.log` dan tidak akan dikirim secara nyata.

## Solusi 2: Menggunakan SMTP (Untuk Production)

Jika Anda ingin mengirim email secara nyata, gunakan konfigurasi SMTP. Contoh untuk Gmail:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@garasi62.co.id
MAIL_FROM_NAME="Garasi62"
```

### Catatan untuk Gmail:
1. Aktifkan "2-Step Verification" di akun Google Anda
2. Buat "App Password" khusus untuk aplikasi ini
3. Gunakan App Password tersebut di `MAIL_PASSWORD`

## Solusi 3: Menggunakan Mailtrap (Untuk Testing)

Mailtrap adalah layanan untuk testing email tanpa mengirim email nyata:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@garasi62.co.id
MAIL_FROM_NAME="Garasi62"
```

## Langkah-langkah:

1. Buka file `.env` di root project
2. Cari bagian konfigurasi mail (sekitar baris 51-59)
3. Pastikan minimal ada konfigurasi berikut:
   ```env
   MAIL_MAILER=log
   MAIL_FROM_ADDRESS=info@garasi62.co.id
   MAIL_FROM_NAME="Garasi62"
   ```
4. Setelah mengubah `.env`, jalankan:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
5. Coba submit form contact lagi

## Troubleshooting

Jika masih error:
1. Periksa file `storage/logs/laravel.log` untuk melihat error detail
2. Pastikan `MAIL_FROM_ADDRESS` adalah email yang valid
3. Jika menggunakan SMTP, pastikan kredensial benar
4. Untuk development, gunakan `MAIL_MAILER=log` terlebih dahulu

