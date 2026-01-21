# Troubleshooting Pusher Connection

## Checklist untuk Memastikan Pusher Terhubung

### 1. Pastikan Credentials Pusher di .env

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=ap1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_USETLS=true

VITE_PUSHER_APP_KEY=${PUSHER_APP_KEY}
VITE_PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}
```

### 2. Clear Cache Setelah Mengubah .env

```powershell
php artisan config:clear
php artisan cache:clear
```

### 3. Cek Browser Console (F12)

Buka browser console dan cek:
- Apakah ada error connection?
- Apakah Echo berhasil initialize?
- Apakah subscription ke channel berhasil?

### 4. Test Broadcast Event

Di Laravel Tinker atau controller, test broadcast:

```php
event(new \App\Events\MessageSent($message, $chat));
```

### 5. Cek Pusher Debug Console

1. Buka Pusher Dashboard
2. Pilih app Anda (Garasi62)
3. Buka tab "Debug Console"
4. Kirim pesan dari aplikasi
5. Event harus muncul di Debug Console

### 6. Cek Laravel Log

```powershell
Get-Content storage/logs/laravel.log -Tail 50
```

Cari error terkait:
- Broadcasting
- Pusher connection
- Event dispatch

## Common Issues

### Issue 1: "Echo is not defined"

**Solusi:**
- Pastikan script Pusher dan Echo ter-load sebelum digunakan
- Cek apakah CDN bisa diakses
- Gunakan local build jika CDN tidak bisa diakses

### Issue 2: "401 Unauthorized" di broadcasting/auth

**Solusi:**
- Pastikan user sudah login
- Pastikan CSRF token ada di meta tag
- Cek route broadcasting/auth sudah terdaftar

### Issue 3: Event tidak muncul di Pusher Debug Console

**Solusi:**
- Pastikan `BROADCAST_DRIVER=pusher` di `.env`
- Pastikan credentials Pusher benar
- Clear config cache
- Cek apakah event benar-benar di-broadcast

### Issue 4: Pesan tidak muncul real-time

**Solusi:**
- Cek browser console untuk error
- Pastikan subscription ke channel berhasil
- Pastikan event name sesuai (MessageSent)
- Cek apakah pesan terkirim ke server (cek network tab)

## Test Manual

### Test 1: Cek Pusher Connection

Di browser console, ketik:
```javascript
console.log('Echo:', window.Echo);
console.log('Pusher:', window.Pusher);
```

Harus muncul object Echo dan Pusher.

### Test 2: Test Subscription

Di browser console, ketik:
```javascript
window.Echo.private('chat.chat_1_2')
    .subscribed(() => console.log('Subscribed!'))
    .error((error) => console.error('Error:', error));
```

Harus muncul "Subscribed!" tanpa error.

### Test 3: Test Broadcast dari Laravel

Di Laravel Tinker:
```php
$message = (object)[
    'id' => 'test123',
    'chat_id' => 'chat_1_2',
    'sender_id' => 1,
    'sender' => (object)['name' => 'Test User'],
    'message' => 'Test message',
    'created_at' => now(),
];

$chat = (object)['id' => 'chat_1_2'];

event(new \App\Events\MessageSent($message, $chat));
```

Event harus muncul di Pusher Debug Console.

## Debug Steps

1. **Cek .env file:**
   - Pastikan semua credentials Pusher sudah benar
   - Pastikan tidak ada typo

2. **Clear cache:**
   ```powershell
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Cek browser console:**
   - Buka F12 → Console
   - Lihat apakah ada error
   - Cek apakah Echo ter-load

4. **Cek Pusher Dashboard:**
   - Buka Debug Console
   - Lihat apakah event terkirim

5. **Cek Laravel log:**
   ```powershell
   Get-Content storage/logs/laravel.log -Tail 100
   ```

6. **Test broadcast manual:**
   - Gunakan Laravel Tinker untuk test broadcast
   - Lihat apakah muncul di Pusher Debug Console

## Jika Masih Tidak Bekerja

1. Pastikan semua credentials Pusher benar
2. Pastikan sudah clear cache
3. Pastikan browser console tidak ada error
4. Cek Pusher Dashboard → Debug Console untuk melihat event
5. Cek Laravel log untuk error

