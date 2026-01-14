# Setup Google reCAPTCHA untuk Contact Form

## Langkah-langkah Setup

### 1. Daftar Google reCAPTCHA

1. Buka: https://www.google.com/recaptcha/admin/create
2. Login dengan akun Google Anda
3. Isi form:
   - **Label**: Garasi62 Contact Form
   - **reCAPTCHA type**: Pilih **reCAPTCHA v2** → **"I'm not a robot" Checkbox**
   - **Domains**: Tambahkan domain Anda (untuk localhost, gunakan `localhost` atau `127.0.0.1`)
4. Terima syarat dan ketentuan
5. Klik **Submit**

### 2. Dapatkan Site Key dan Secret Key

Setelah mendaftar, Anda akan mendapatkan:
- **Site Key** (untuk frontend)
- **Secret Key** (untuk backend)

### 3. Update file `.env`

Tambahkan konfigurasi berikut di file `.env`:

```env
RECAPTCHA_SITE_KEY=your-site-key-here
RECAPTCHA_SECRET_KEY=your-secret-key-here
```

**PENTING:** 
- Ganti `your-site-key-here` dengan Site Key dari Google
- Ganti `your-secret-key-here` dengan Secret Key dari Google
- Jangan share Secret Key ke publik!

### 4. Clear Cache

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Jalankan Migration

Jalankan migration untuk membuat tabel `contacts`:

```bash
php artisan migrate
```

## Fitur yang Sudah Ditambahkan

### ✅ Validasi Email 1x Per Hari
- Setiap email hanya bisa mengirim 1 pesan per hari
- Jika mencoba mengirim lebih dari 1x, akan ditolak dengan pesan error

### ✅ Sanitasi Input
- Semua input di-sanitize untuk mencegah XSS dan injection
- HTML entities di-escape
- Karakter kontrol dihapus
- Whitespace di-trim

### ✅ Google reCAPTCHA
- Menggunakan reCAPTCHA v2 (checkbox)
- Verifikasi dilakukan di server-side
- Mencegah spam dan bot

### ✅ Validasi Lengkap
- Validasi format email (RFC dan DNS)
- Validasi panjang karakter (min/max)
- Validasi required fields
- Pesan error yang user-friendly

### ✅ Tracking
- Semua pesan disimpan di database
- IP address dicatat untuk tracking
- Timestamp dicatat untuk validasi 1x per hari

## Testing

### Untuk Development (tanpa reCAPTCHA)

Jika Anda belum setup reCAPTCHA, sistem akan skip validasi reCAPTCHA jika `RECAPTCHA_SECRET_KEY` tidak di-set di `.env`. Ini memudahkan development.

### Untuk Production

Pastikan:
1. `RECAPTCHA_SITE_KEY` dan `RECAPTCHA_SECRET_KEY` sudah di-set di `.env`
2. Domain sudah ditambahkan di Google reCAPTCHA admin
3. Migration sudah dijalankan
4. Cache sudah di-clear

## Troubleshooting

### Error "Verifikasi reCAPTCHA gagal"

1. Pastikan `RECAPTCHA_SECRET_KEY` benar di `.env`
2. Pastikan domain sudah ditambahkan di Google reCAPTCHA admin
3. Clear cache: `php artisan config:clear`
4. Cek log: `storage/logs/laravel.log`

### Error "Email ini sudah mengirim pesan hari ini"

Ini adalah fitur yang sengaja dibuat. Setiap email hanya bisa mengirim 1 pesan per hari. Untuk testing, gunakan email berbeda atau tunggu sampai besok.

### reCAPTCHA tidak muncul

1. Pastikan `RECAPTCHA_SITE_KEY` sudah di-set di `.env`
2. Pastikan script reCAPTCHA sudah ter-load (cek browser console)
3. Pastikan domain sudah ditambahkan di Google reCAPTCHA admin

## Catatan Penting

- **Jangan share Secret Key** ke publik atau commit ke repository
- **Domain harus match** dengan yang didaftarkan di Google reCAPTCHA
- **Untuk localhost**, gunakan `localhost` atau `127.0.0.1` di domain list
- **Migration harus dijalankan** sebelum menggunakan fitur ini

