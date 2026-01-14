# Troubleshooting Upload Gambar Mobil dan Blog

## Masalah yang Sering Terjadi

### 1. Gambar tidak bisa diupload

**Penyebab:**
- Folder storage tidak ada atau tidak bisa ditulis
- Permission folder storage salah
- Storage link belum dibuat
- Max upload size di PHP terlalu kecil

**Solusi:**

1. **Pastikan folder storage ada:**
   ```bash
   # Folder harus ada:
   storage/app/public/cars
   storage/app/public/blogs
   ```

2. **Buat storage link:**
   ```bash
   php artisan storage:link
   ```

3. **Pastikan permission folder benar (Windows):**
   - Folder `storage/app/public` harus bisa ditulis
   - Jika menggunakan XAMPP, biasanya sudah otomatis bisa ditulis

4. **Cek PHP configuration:**
   ```bash
   php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize');"
   php -r "echo 'post_max_size: ' . ini_get('post_max_size');"
   ```
   - Pastikan minimal 5MB (untuk upload gambar maksimal 5MB)
   - Jika kurang, edit `php.ini`:
     ```
     upload_max_filesize = 40M
     post_max_size = 40M
     ```

### 2. Error "Gagal mengupload gambar"

**Penyebab:**
- Folder storage tidak bisa ditulis
- Disk space penuh
- Format file tidak didukung

**Solusi:**

1. **Cek error di log:**
   ```bash
   tail -n 50 storage/logs/laravel.log
   ```

2. **Pastikan format file didukung:**
   - JPG, JPEG
   - PNG
   - GIF
   - WEBP

3. **Cek ukuran file:**
   - Maksimal 5MB per gambar
   - Untuk mobil: maksimal 6 gambar
   - Untuk blog: 1 gambar

### 3. Gambar tidak muncul setelah diupload

**Penyebab:**
- Storage link belum dibuat
- Path gambar salah
- Permission folder public/storage salah

**Solusi:**

1. **Buat storage link:**
   ```bash
   php artisan storage:link
   ```

2. **Cek apakah link ada:**
   - Di Windows: `public/storage` harus link ke `storage/app/public`
   - Jika tidak ada, jalankan `php artisan storage:link` lagi

3. **Cek permission:**
   - Folder `public/storage` harus bisa dibaca

### 4. Error Validasi

**Penyebab:**
- Format file tidak sesuai
- Ukuran file terlalu besar
- Jumlah gambar melebihi batas

**Solusi:**

1. **Untuk Mobil:**
   - Minimal 1 gambar
   - Maksimal 6 gambar
   - Format: JPG, PNG, GIF, WEBP
   - Maksimal 5MB per gambar

2. **Untuk Blog:**
   - Gambar opsional
   - Format: JPG, PNG, GIF, WEBP
   - Maksimal 5MB

## Langkah-langkah Perbaikan

1. **Buat folder storage jika belum ada:**
   ```bash
   mkdir -p storage/app/public/cars
   mkdir -p storage/app/public/blogs
   ```

2. **Buat storage link:**
   ```bash
   php artisan storage:link
   ```

3. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

4. **Cek error di log:**
   ```bash
   tail -n 100 storage/logs/laravel.log
   ```

5. **Test upload:**
   - Coba upload gambar kecil dulu (misalnya 100KB)
   - Jika berhasil, coba gambar yang lebih besar
   - Pastikan format file didukung

## Catatan Penting

- **Windows/XAMPP:** Biasanya tidak ada masalah permission, tapi pastikan folder ada
- **Linux:** Pastikan permission folder `storage/app/public` adalah 755 atau 775
- **Format file:** Pastikan file benar-benar gambar (bukan file lain yang diubah ekstensinya)
- **Ukuran file:** Jika gambar terlalu besar, kompres dulu sebelum upload

## Jika Masih Error

1. Cek file `storage/logs/laravel.log` untuk detail error
2. Pastikan semua langkah di atas sudah dilakukan
3. Coba dengan gambar yang berbeda
4. Pastikan browser mendukung upload file (tidak ada extension yang memblokir)

