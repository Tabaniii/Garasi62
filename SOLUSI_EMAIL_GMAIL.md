# Solusi Email Tidak Masuk ke Gmail - Garasi62

## Masalah yang Ditemukan

Berdasarkan log error, masalahnya adalah:
```
Failed to authenticate on SMTP server with username "taqyadriano@gmail.com"
535-5.7.8 Username and Password not accepted
```

**Penyebab:** Gmail menolak autentikasi karena menggunakan password Gmail biasa, bukan **App Password**.

## Solusi: Setup Gmail dengan App Password

Gmail **tidak lagi mengizinkan** penggunaan password biasa untuk aplikasi. Anda **HARUS** menggunakan **App Password**.

### Langkah 1: Aktifkan 2-Step Verification

1. Buka: https://myaccount.google.com/security
2. Login dengan akun Gmail yang akan digunakan (tabaniakmal@gmail.com atau taqyadriano@gmail.com)
3. Scroll ke bagian **"2-Step Verification"**
4. Klik **"Get started"** atau **"Turn on"**
5. Ikuti langkah-langkah untuk mengaktifkan 2-Step Verification

**PENTING:** Tanpa 2-Step Verification, Anda tidak bisa membuat App Password!

### Langkah 2: Buat App Password

1. Setelah 2-Step Verification aktif, buka: https://myaccount.google.com/apppasswords
2. Login jika diminta
3. Di bagian **"Select app"**, pilih **"Mail"**
4. Di bagian **"Select device"**, pilih **"Other (Custom name)"**
5. Ketik nama: **"Garasi62"** atau **"Laravel App"**
6. Klik **"Generate"**
7. **SALIN** password yang dihasilkan (16 karakter, contoh: `abcd efgh ijkl mnop`)

**PENTING:** 
- App Password hanya muncul **sekali saja**
- Simpan dengan aman
- Jangan gunakan spasi (hapus semua spasi)
- Bukan password Gmail Anda, tapi password khusus untuk aplikasi

### Langkah 3: Update file .env

Buka file `.env` di root project dan pastikan konfigurasi email seperti ini:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tabaniakmal@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tabaniakmal@gmail.com
MAIL_FROM_NAME="Garasi62"
```

**Catatan Penting:**
- `MAIL_USERNAME`: Email Gmail yang digunakan (tabaniakmal@gmail.com)
- `MAIL_PASSWORD`: **App Password** yang dibuat di Langkah 2 (16 karakter, **TANPA SPASI**)
- `MAIL_FROM_ADDRESS`: Bisa sama dengan MAIL_USERNAME
- `MAIL_ENCRYPTION`: Harus `tls` (bukan `ssl`)

### Langkah 4: Clear Cache Config

Setelah mengubah `.env`, **WAJIB** clear cache:

```bash
php artisan config:clear
php artisan cache:clear
```

Atau di Windows PowerShell:
```powershell
php artisan config:clear
php artisan cache:clear
```

### Langkah 5: Test Email

1. Buka halaman contact form
2. Isi form dan submit
3. Cek email di inbox `tabaniakmal@gmail.com`
4. **Jangan lupa cek folder SPAM** jika tidak ada di inbox

## Alternatif: Gunakan Log Mailer untuk Testing

Jika Anda ingin testing tanpa setup Gmail yang rumit, ubah di `.env`:

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=tabaniakmal@gmail.com
MAIL_FROM_NAME="Garasi62"
```

Dengan konfigurasi ini, email akan disimpan di `storage/logs/laravel.log` dan tidak dikirim secara nyata. Berguna untuk development/testing.

Jangan lupa clear cache setelah mengubah:
```bash
php artisan config:clear
php artisan cache:clear
```

## Troubleshooting

### Masih error setelah menggunakan App Password?

1. **Pastikan App Password benar:**
   - 16 karakter tanpa spasi
   - Bukan password Gmail biasa
   - Sudah di-copy dengan benar (tanpa karakter tambahan)

2. **Pastikan 2-Step Verification aktif:**
   - Buka https://myaccount.google.com/security
   - Cek apakah "2-Step Verification" menunjukkan "On"

3. **Cek log error:**
   ```powershell
   Get-Content storage/logs/laravel.log -Tail 50
   ```

4. **Clear cache lagi:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

5. **Cek folder SPAM:**
   - Email mungkin masuk ke folder spam
   - Cek juga folder "All Mail"

### Error "Less secure app access"?

Gmail sudah tidak lagi mendukung "Less secure app access". Anda **HARUS** menggunakan App Password, tidak ada cara lain.

### Email masuk tapi di SPAM?

Ini normal untuk email dari aplikasi. Pengguna perlu cek folder spam, atau Anda bisa:
- Gunakan domain email sendiri (bukan Gmail)
- Setup SPF/DKIM records
- Gunakan layanan email profesional (SendGrid, Mailgun, dll)

## Ringkasan Konfigurasi .env

```env
# Untuk Production (Gmail dengan App Password)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tabaniakmal@gmail.com
MAIL_PASSWORD=your-16-char-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tabaniakmal@gmail.com
MAIL_FROM_NAME="Garasi62"

# Untuk Development/Testing (Log ke file)
# MAIL_MAILER=log
# MAIL_FROM_ADDRESS=tabaniakmal@gmail.com
# MAIL_FROM_NAME="Garasi62"
```

## Checklist

- [ ] 2-Step Verification sudah aktif
- [ ] App Password sudah dibuat (16 karakter)
- [ ] File `.env` sudah diupdate dengan App Password (tanpa spasi)
- [ ] Sudah menjalankan `php artisan config:clear`
- [ ] Sudah menjalankan `php artisan cache:clear`
- [ ] Sudah test submit form contact
- [ ] Sudah cek inbox dan folder spam




