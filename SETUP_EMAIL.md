# Panduan Setup Email untuk tabaniakmal@gmail.com

## Masalah yang Ditemukan
Email tidak masuk karena konfigurasi email di `.env` belum benar atau SMTP belum dikonfigurasi dengan benar.

## Solusi Cepat (Untuk Testing)

Buka file `.env` di root project dan ubah konfigurasi email menjadi:

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=tabaniakmal@gmail.com
MAIL_FROM_NAME="Garasi62"
```

Dengan konfigurasi ini, email akan disimpan di `storage/logs/laravel.log` dan tidak akan dikirim secara nyata. Ini berguna untuk testing.

Setelah mengubah `.env`, jalankan:
```bash
php artisan config:clear
php artisan cache:clear
```

## Solusi untuk Mengirim Email Nyata (Gmail)

Jika Anda ingin email benar-benar dikirim ke `tabaniakmal@gmail.com`, ikuti langkah berikut:

### 1. Aktifkan 2-Step Verification di Gmail
- Buka https://myaccount.google.com/security
- Aktifkan "2-Step Verification"

### 2. Buat App Password
- Setelah 2-Step Verification aktif, buka https://myaccount.google.com/apppasswords
- Pilih "Mail" dan "Other (Custom name)"
- Masukkan nama: "Garasi62"
- Klik "Generate"
- Salin password yang dihasilkan (16 karakter tanpa spasi)

### 3. Update file `.env`
Ubah konfigurasi email menjadi:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tabaniakmal@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tabaniakmal@gmail.com
MAIL_FROM_NAME="Garasi62"
```

**PENTING:** Ganti `xxxx xxxx xxxx xxxx` dengan App Password yang Anda buat di langkah 2 (tanpa spasi).

### 4. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Test Email
Coba submit form contact dan cek email di inbox `tabaniakmal@gmail.com`.

## Troubleshooting

Jika email masih tidak masuk:

1. **Cek log error:**
   ```bash
   tail -n 50 storage/logs/laravel.log
   ```

2. **Pastikan App Password benar:**
   - App Password harus 16 karakter tanpa spasi
   - Bukan password Gmail biasa

3. **Cek spam folder:**
   - Email mungkin masuk ke folder spam

4. **Untuk testing, gunakan log mailer:**
   - Set `MAIL_MAILER=log` di `.env`
   - Email akan tersimpan di `storage/logs/laravel.log`

## Catatan Penting

- Email dari contact form sekarang **selalu dikirim ke `tabaniakmal@gmail.com`**
- Kode sudah diupdate untuk memastikan email dikirim ke alamat yang benar
- Pastikan konfigurasi SMTP benar jika ingin mengirim email nyata

