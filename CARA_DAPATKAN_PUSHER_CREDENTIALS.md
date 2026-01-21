# Cara Mendapatkan Pusher Credentials (APP_ID, APP_KEY, APP_SECRET)

## 📋 Langkah-Langkah Mendapatkan Kredensial Pusher

### Langkah 1: Daftar/Buat Akun Pusher

1. Buka website Pusher: **https://pusher.com/**
2. Klik **"Sign Up"** atau **"Get Started"** di pojok kanan atas
3. Daftar dengan:
   - Email Anda
   - Password
   - Atau gunakan Google/GitHub untuk sign up cepat

---

### Langkah 2: Buat App Baru di Pusher

1. Setelah login, Anda akan masuk ke **Dashboard Pusher**
2. Klik tombol **"Create app"** atau **"Create new app"**
3. Isi form:
   - **App name**: `Garasi62` (atau nama lain yang Anda inginkan)
   - **Cluster**: Pilih yang terdekat dengan lokasi Anda
     - `ap1` (Asia Pacific - Singapore) - **RECOMMENDED untuk Indonesia**
     - `ap2` (Asia Pacific - Mumbai)
     - `ap3` (Asia Pacific - Tokyo)
     - `ap4` (Asia Pacific - Sydney)
     - `eu` (Europe)
     - `us2` (US East)
     - `us3` (US West)
   - **Front-end tech**: Pilih **Vanilla JS** atau **React**
   - **Back-end tech**: Pilih **Laravel**
4. Klik **"Create app"**

---

### Langkah 3: Dapatkan Credentials

Setelah app dibuat, Anda akan langsung melihat **App Keys** di dashboard:

1. Di halaman dashboard app Anda, scroll ke bagian **"App Keys"**
2. Anda akan melihat 4 informasi penting:
   - **App ID**: Contoh: `1234567`
   - **Key**: Contoh: `a1b2c3d4e5f6g7h8i9j0`
   - **Secret**: Contoh: `1a2b3c4d5e6f7g8h9i0j`
   - **Cluster**: Contoh: `ap1`

---

### Langkah 4: Copy Credentials ke File .env

Buka file `.env` di root project (`C:\xampp\htdocs\projek_01\Garasi62\.env`) dan tambahkan/update:

```env
# Pusher Configuration
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=1234567
PUSHER_APP_KEY=a1b2c3d4e5f6g7h8i9j0
PUSHER_APP_SECRET=1a2b3c4d5e6f7g8h9i0j
PUSHER_APP_CLUSTER=ap1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_USETLS=true

# Vite Pusher Configuration (untuk frontend)
VITE_PUSHER_APP_KEY=${PUSHER_APP_KEY}
VITE_PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}
VITE_PUSHER_HOST=${PUSHER_HOST}
VITE_PUSHER_PORT=${PUSHER_PORT}
VITE_PUSHER_SCHEME=${PUSHER_SCHEME}
```

**PENTING:**
- Ganti nilai di atas dengan credentials yang Anda dapatkan dari Pusher Dashboard
- Jangan share credentials ini ke publik!
- `PUSHER_APP_CLUSTER` harus sesuai dengan cluster yang Anda pilih saat membuat app

---

### Langkah 5: Clear Cache Laravel

Setelah mengubah `.env`, jalankan:

```powershell
php artisan config:clear
php artisan cache:clear
```

---

### Langkah 6: Build Assets (untuk Vite)

Karena kita menggunakan Vite untuk frontend, jalankan:

```powershell
npm run dev
```

Atau untuk production:

```powershell
npm run build
```

---

## 🎯 Ringkasan Lokasi Credentials di Pusher Dashboard

1. **Login ke Pusher**: https://pusher.com/
2. **Dashboard** → Pilih app Anda
3. **Tab "App Keys"** → Scroll ke bawah
4. Copy:
   - **App ID** → `PUSHER_APP_ID`
   - **Key** → `PUSHER_APP_KEY`
   - **Secret** → `PUSHER_APP_SECRET`
   - **Cluster** → `PUSHER_APP_CLUSTER`

---

## 💡 Tips

### Free Plan Pusher
- Pusher menyediakan **free plan** dengan:
  - 200,000 messages per day
  - 100 concurrent connections
  - Unlimited channels
  - Cocok untuk development dan testing

### Security
- **JANGAN** commit file `.env` ke Git!
- Credentials Pusher adalah rahasia, jangan share ke publik
- Jika credentials ter-expose, segera regenerate di Pusher Dashboard

### Regenerate Credentials
Jika credentials ter-expose atau hilang:
1. Buka Pusher Dashboard
2. Pilih app Anda
3. Klik **"App Settings"**
4. Scroll ke **"App Keys"**
5. Klik **"Regenerate"** untuk membuat credentials baru

---

## 🔍 Screenshot Lokasi (Deskripsi)

Di Pusher Dashboard, setelah membuat app:

```
┌─────────────────────────────────────┐
│  Garasi62 App                       │
├─────────────────────────────────────┤
│  [Overview] [App Keys] [Settings]  │
├─────────────────────────────────────┤
│                                     │
│  App Keys                          │
│  ────────────────────────────────   │
│                                     │
│  App ID:                            │
│  1234567                            │
│                                     │
│  Key:                               │
│  a1b2c3d4e5f6g7h8i9j0               │
│                                     │
│  Secret:                            │
│  1a2b3c4d5e6f7g8h9i0j               │
│                                     │
│  Cluster:                           │
│  ap1                                │
│                                     │
└─────────────────────────────────────┘
```

---

## ✅ Checklist

Pastikan semua sudah dilakukan:

- [ ] Sudah daftar/login ke Pusher
- [ ] Sudah membuat app baru di Pusher
- [ ] Sudah copy App ID, Key, Secret, dan Cluster
- [ ] Sudah update file `.env` dengan credentials
- [ ] Sudah menjalankan `php artisan config:clear`
- [ ] Sudah menjalankan `php artisan cache:clear`
- [ ] Sudah menjalankan `npm run dev` atau `npm run build`
- [ ] Sudah test chat real-time

---

## 🆘 Troubleshooting

### Error "Pusher connection failed"
- Pastikan credentials di `.env` benar
- Pastikan cluster sesuai dengan yang dipilih di Pusher
- Clear cache: `php artisan config:clear`

### Error "VITE_PUSHER_APP_KEY is not defined"
- Pastikan sudah menambahkan `VITE_PUSHER_APP_KEY` di `.env`
- Jalankan `npm run dev` atau `npm run build` lagi
- Restart development server

### Chat tidak real-time
- Pastikan `BROADCAST_DRIVER=pusher` di `.env`
- Pastikan sudah build assets dengan `npm run dev`
- Cek browser console untuk error
- Pastikan credentials Pusher benar

---

## 📞 Bantuan Lebih Lanjut

- **Pusher Documentation**: https://pusher.com/docs/
- **Laravel Broadcasting**: https://laravel.com/docs/broadcasting
- **Pusher Support**: https://support.pusher.com/

---

## 🎉 Setelah Setup

Setelah semua credentials di-set dengan benar:
1. Chat akan bekerja secara **real-time** tanpa perlu refresh
2. Pesan akan muncul **langsung** saat dikirim
3. Badge unread akan update **otomatis**
4. Chat list akan update **real-time** juga

Selamat! Chat real-time Anda sudah siap digunakan! 🚀

