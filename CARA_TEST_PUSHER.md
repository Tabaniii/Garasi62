# Cara Test Pusher Connection

## ⚠️ PENTING: Console.log harus di Browser, BUKAN PowerShell!

**JANGAN** jalankan `console.log` di PowerShell/Command Prompt. Itu adalah perintah JavaScript yang harus dijalankan di **Browser Console**.

## Cara Test di Browser Console

### Langkah 1: Buka Browser Console

1. Buka halaman chat di browser
2. Tekan **F12** atau **Ctrl+Shift+I** (Windows) / **Cmd+Option+I** (Mac)
3. Klik tab **"Console"**

### Langkah 2: Test Echo dan Pusher

Di browser console, ketik dan tekan Enter:

```javascript
console.log('Echo:', window.Echo);
```

```javascript
console.log('Pusher:', window.Pusher);
```

Harus muncul object Echo dan Pusher (bukan error).

### Langkah 3: Test Subscription

Di browser console, ketik:

```javascript
window.Echo.private('chat.chat_1_2')
    .subscribed(() => console.log('✅ Subscribed!'))
    .error((error) => console.error('❌ Error:', error));
```

Ganti `chat_1_2` dengan chat ID yang sebenarnya.

### Langkah 4: Test Broadcast dari Laravel

Buka terminal PowerShell dan jalankan:

```powershell
php artisan tinker
```

Lalu di Tinker, ketik:

```php
$message = (object)[
    'id' => 'test_' . time(),
    'chat_id' => 'chat_1_2',
    'sender_id' => 1,
    'sender' => (object)['name' => 'Test User'],
    'sender_name' => 'Test User',
    'message' => 'Test message from Tinker',
    'created_at' => now(),
];

$chat = (object)['id' => 'chat_1_2'];

event(new \App\Events\MessageSent($message, $chat));
```

Event harus muncul di Pusher Debug Console.

## Checklist Debugging

- [ ] Browser console tidak ada error JavaScript
- [ ] `window.Echo` terdefinisi (bukan undefined)
- [ ] `window.Pusher` terdefinisi (bukan undefined)
- [ ] Subscription berhasil (lihat console log)
- [ ] Event muncul di Pusher Debug Console saat kirim pesan
- [ ] Laravel log tidak ada error

## Common Errors

### Error: "Echo is not defined"
**Solusi:** Pastikan script Pusher dan Echo ter-load. Cek Network tab di browser.

### Error: "401 Unauthorized" di `/broadcasting/auth`
**Solusi:** 
- Pastikan user sudah login
- Pastikan CSRF token ada di meta tag
- Cek apakah route `/broadcasting/auth` bisa diakses

### Error: "Undefined property: stdClass::$sender"
**Solusi:** Sudah diperbaiki dengan menambahkan `sender_name` langsung di message object.

### Event tidak muncul di Pusher Debug Console
**Solusi:**
- Pastikan `BROADCAST_DRIVER=pusher` di `.env`
- Pastikan credentials Pusher benar
- Clear config cache: `php artisan config:clear`
- Cek Laravel log untuk error broadcast

## Test Sekarang

1. **Buka halaman chat di browser**
2. **Tekan F12** → Console tab
3. **Lihat console log** - harus ada:
   - `Initializing Echo with key: ...`
   - `Echo initialized: ...`
   - `Chat ID: chat_...`
   - `Connecting to Pusher channel: chat.chat_...`
   - `Successfully subscribed to channel: chat.chat_...`

4. **Kirim pesan** dari form chat
5. **Cek Pusher Debug Console** - event harus muncul

Jika masih ada masalah, screenshot:
- Browser console (F12)
- Pusher Debug Console
- Laravel log (`storage/logs/laravel.log`)

