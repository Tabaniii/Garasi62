# Cara Mendapatkan App Password Gmail untuk Garasi62

## ⚠️ PENTING: Gmail Tidak Menerima Password Biasa!

Gmail **tidak lagi mengizinkan** penggunaan password Gmail biasa untuk aplikasi. Anda **HARUS** menggunakan **App Password** (Password Aplikasi).

---

## 📋 Langkah-Langkah Mendapatkan App Password

### Langkah 1: Aktifkan 2-Step Verification (2FA)

**WAJIB!** Tanpa 2-Step Verification, Anda tidak bisa membuat App Password.

1. Buka: **https://myaccount.google.com/security**
2. Login dengan akun Gmail Anda (`tabaniakmal@gmail.com`)
3. Scroll ke bagian **"2-Step Verification"** atau **"Verifikasi 2 Langkah"**
4. Klik **"Get started"** atau **"Mulai"**
5. Ikuti langkah-langkah:
   - Masukkan password Gmail Anda
   - Pilih metode verifikasi (SMS ke nomor HP atau Google Authenticator)
   - Masukkan kode verifikasi yang diterima
   - Klik **"Turn on"** atau **"Aktifkan"**

**Catatan:** 
- Jika sudah aktif, akan muncul tulisan "On" atau "Aktif"
- Jika belum aktif, ikuti langkah di atas

---

### Langkah 2: Buat App Password

Setelah 2-Step Verification aktif:

1. Buka: **https://myaccount.google.com/apppasswords**
   - Atau: https://myaccount.google.com/security → Scroll ke "App passwords" → Klik
   
2. Login jika diminta

3. Di bagian **"Select app"**, pilih: **"Mail"**

4. Di bagian **"Select device"**, pilih: **"Other (Custom name)"** atau **"Lainnya (Nama khusus)"**

5. Ketik nama aplikasi: **"Garasi62"** atau **"Laravel App"**

6. Klik **"Generate"** atau **"Buat"**

7. **SALIN PASSWORD YANG DIHASILKAN!**
   - Password akan muncul seperti: `dubiwdphvywaflop` (16 karakter)
   - **HAPUS SEMUA SPASI** jika ada
   - Password hanya muncul **sekali saja**, simpan dengan aman!

---

### Langkah 3: Update File .env

Buka file `.env` di root project (`C:\xampp\htdocs\projek_01\Garasi62\.env`) dan pastikan konfigurasi seperti ini:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tabaniakmal@gmail.com
MAIL_PASSWORD=dubiwdphvywaflop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tabaniakmal@gmail.com
MAIL_FROM_NAME="Garasi62"
```

**PENTING:**
- ✅ `MAIL_PASSWORD`: Gunakan App Password yang Anda dapatkan (contoh: `dubiwdphvywaflop`)
- ✅ **TANPA SPASI** di password
- ✅ `MAIL_ENCRYPTION`: Harus `tls` (bukan `ssl`)
- ✅ `MAIL_PORT`: Harus `587` untuk TLS

---

### Langkah 4: Clear Cache Laravel

Setelah mengubah `.env`, **WAJIB** clear cache:

```powershell
php artisan config:clear
php artisan cache:clear
```

Atau di Command Prompt:
```cmd
php artisan config:clear
php artisan cache:clear
```

---

### Langkah 5: Test Email

1. Buka halaman forgot password: `http://localhost/Garasi62/public/password/reset`
2. Masukkan email yang terdaftar
3. Cek email di inbox `tabaniakmal@gmail.com`
4. **Jangan lupa cek folder SPAM** jika tidak ada di inbox

---

## 🔍 Troubleshooting

### Masih Error "Username and Password not accepted"?

1. **Pastikan App Password benar:**
   - ✅ 16 karakter tanpa spasi
   - ✅ Bukan password Gmail biasa
   - ✅ Sudah di-copy dengan benar (tanpa karakter tambahan di awal/akhir)

2. **Pastikan 2-Step Verification aktif:**
   - Buka: https://myaccount.google.com/security
   - Cek apakah "2-Step Verification" menunjukkan **"On"**

3. **Cek konfigurasi .env:**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=tabaniakmal@gmail.com
   MAIL_PASSWORD=dubiwdphvywaflop
   MAIL_ENCRYPTION=tls
   ```

4. **Clear cache lagi:**
   ```powershell
   php artisan config:clear
   php artisan cache:clear
   ```

5. **Cek log error:**
   ```powershell
   Get-Content storage/logs/laravel.log -Tail 50
   ```

---

## 📝 Checklist

Pastikan semua sudah dilakukan:

- [ ] 2-Step Verification sudah aktif di Gmail
- [ ] App Password sudah dibuat (16 karakter)
- [ ] App Password sudah di-copy dan disimpan dengan aman
- [ ] File `.env` sudah diupdate dengan App Password (tanpa spasi)
- [ ] Sudah menjalankan `php artisan config:clear`
- [ ] Sudah menjalankan `php artisan cache:clear`
- [ ] Sudah test forgot password
- [ ] Sudah cek inbox dan folder spam

---

## 💡 Tips

1. **Simpan App Password dengan aman** - Password hanya muncul sekali!
2. **Jika lupa App Password**, buat yang baru di https://myaccount.google.com/apppasswords
3. **Untuk testing**, bisa gunakan `MAIL_MAILER=log` di `.env` (email akan tersimpan di `storage/logs/laravel.log`)
4. **Email masuk ke SPAM?** Ini normal untuk email dari aplikasi. Pengguna perlu cek folder spam.

---

## 🎯 Konfigurasi .env yang Benar

```env
# Email Configuration untuk Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tabaniakmal@gmail.com
MAIL_PASSWORD=dubiwdphvywaflop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tabaniakmal@gmail.com
MAIL_FROM_NAME="Garasi62"
```

**Ganti `dubiwdphvywaflop` dengan App Password Anda yang sebenarnya!**

---

## 📞 Bantuan Lebih Lanjut

Jika masih error setelah mengikuti semua langkah:
1. Cek log error: `storage/logs/laravel.log`
2. Pastikan tidak ada typo di `.env`
3. Pastikan App Password benar-benar 16 karakter tanpa spasi
4. Coba buat App Password baru jika yang lama tidak bekerja

