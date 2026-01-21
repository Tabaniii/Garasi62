# Debug Real-time Chat

## Cara Test Real-time Chat

### 1. Buka Browser Console (F12)

Di kedua browser yang berbeda, buka console dan cek:

**Browser 1 (Pengirim):**
- `✅ Echo initialized`
- `🔌 Connecting to Pusher channel: chat.chat_X_Y`
- `✅ Successfully subscribed to channel: chat.chat_X_Y`
- `✅ Pusher connected`
- `✅ Message sent and displayed immediately: [message_id]`

**Browser 2 (Penerima):**
- `✅ Echo initialized`
- `🔌 Connecting to Pusher channel: chat.chat_X_Y`
- `✅ Successfully subscribed to channel: chat.chat_X_Y`
- `✅ Pusher connected`
- `📨 Message received via Pusher: [event]` ← **INI HARUS MUNCUL**
- `✅ New message displayed via Pusher: [message_id]`

### 2. Cek Laravel Log

```powershell
Get-Content storage\logs\laravel.log -Tail 20
```

Harus muncul:
- `Broadcasting message event`
- `Message broadcasted successfully`

### 3. Cek Pusher Debug Console

1. Buka Pusher Dashboard
2. Pilih app Anda
3. Buka tab "Debug Console"
4. Kirim pesan dari aplikasi
5. Event harus muncul di Debug Console dengan nama `MessageSent`

### 4. Troubleshooting

#### Jika subscription gagal:
- Cek apakah `✅ Successfully subscribed` muncul di console
- Cek apakah ada error `❌ Echo subscription error`
- Cek apakah user sudah login di kedua browser

#### Jika event tidak diterima:
- Cek apakah `📨 Message received via Pusher` muncul di console
- Cek apakah event muncul di Pusher Debug Console
- Cek Laravel log apakah `Message broadcasted successfully` muncul

#### Jika pesan tidak muncul:
- Cek apakah `✅ New message displayed via Pusher` muncul di console
- Cek apakah `renderedMessageIds` sudah memiliki message ID
- Cek apakah `appendMessage` function dipanggil

### 5. Test Manual di Console

Di browser console (browser penerima), ketik:

```javascript
// Cek apakah Echo terhubung
console.log('Echo:', window.Echo);
console.log('Pusher:', window.Pusher);

// Cek channel
const channel = window.Echo.private('chat.chat_X_Y');
console.log('Channel:', channel);

// Test manual listen
channel.listen('MessageSent', (e) => {
    console.log('Manual test - Message received:', e);
});
```

Ganti `chat_X_Y` dengan chat ID yang sebenarnya.

