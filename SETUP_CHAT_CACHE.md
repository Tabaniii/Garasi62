# Setup Chat Cache Configuration

## Konfigurasi Cache untuk Chat

Chat menggunakan Laravel Cache untuk menyimpan histori (bukan database). Ada 2 opsi:

### Opsi 1: File Cache (Recommended - Lebih Sederhana)

Tambahkan di file `.env`:

```env
CACHE_STORE=file
```

**Keuntungan:**
- Tidak perlu setup database cache table
- Lebih sederhana
- File cache otomatis tersimpan di `storage/framework/cache`

### Opsi 2: Database Cache (Default)

Jika menggunakan database cache, pastikan sudah ada tabel cache:

```bash
php artisan cache:table
php artisan migrate
```

Dan di `.env`:
```env
CACHE_STORE=database
```

## Setelah Mengubah .env

**WAJIB** clear cache config:

```powershell
php artisan config:clear
php artisan cache:clear
```

## Test Chat

1. Buka halaman chat
2. Kirim pesan
3. Pesan harus muncul real-time
4. Refresh halaman - pesan harus tetap ada (dari cache)

## Troubleshooting

### Chat tidak terkirim?

1. **Pastikan Pusher credentials sudah di-set di `.env`:**
   ```env
   BROADCAST_DRIVER=pusher
   PUSHER_APP_ID=your-app-id
   PUSHER_APP_KEY=your-app-key
   PUSHER_APP_SECRET=your-app-secret
   PUSHER_APP_CLUSTER=ap1
   ```

2. **Pastikan sudah build assets:**
   ```powershell
   npm run dev
   ```
   Atau untuk production:
   ```powershell
   npm run build
   ```

3. **Clear cache:**
   ```powershell
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Cek browser console** untuk error JavaScript

5. **Cek Laravel log:**
   ```powershell
   Get-Content storage/logs/laravel.log -Tail 50
   ```

### Pesan tidak tersimpan di cache?

1. Pastikan `CACHE_STORE` sudah di-set di `.env`
2. Pastikan folder `storage/framework/cache` bisa ditulis
3. Clear cache dan coba lagi

### Real-time tidak bekerja?

1. Pastikan Pusher credentials benar
2. Pastikan sudah build assets dengan `npm run dev`
3. Cek browser console untuk error connection Pusher
4. Pastikan `BROADCAST_DRIVER=pusher` di `.env`

