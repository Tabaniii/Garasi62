<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactMail;
use App\Models\Contact;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            // Simpan URL yang diminta untuk redirect setelah login
            $request->session()->put('url.intended', route('contact'));
            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu untuk mengirim pesan.');
        }

        // Gunakan email dari user yang login untuk keamanan
        $user = auth()->user();
        
        // Sanitasi input
        $sanitized = [
            'name' => $this->sanitizeInput($request->input('name', $user->name ?? '')),
            'email' => $user->email, // Gunakan email dari user yang login
            'subject' => $this->sanitizeInput($request->input('subject')),
            'message' => $this->sanitizeInput($request->input('message')),
            'g-recaptcha-response' => $request->input('g-recaptcha-response'),
        ];

        // Validasi
        $validator = Validator::make($sanitized, [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email:rfc,dns|max:255',
            'subject' => 'required|string|max:255|min:3',
            'message' => 'required|string|min:10|max:5000',
            'g-recaptcha-response' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subject wajib diisi.',
            'subject.min' => 'Subject minimal 3 karakter.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min' => 'Pesan minimal 10 karakter.',
            'message.max' => 'Pesan maksimal 5000 karakter.',
            'g-recaptcha-response.required' => 'Mohon verifikasi bahwa Anda bukan robot.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contact')
                ->withErrors($validator)
                ->withInput($request->except('g-recaptcha-response'));
        }

        // Validasi reCAPTCHA
        $recaptchaResponse = $this->verifyRecaptcha($sanitized['g-recaptcha-response'], $request->ip());
        if (!$recaptchaResponse['success']) {
            return redirect()->route('contact')
                ->with('error', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.')
                ->withInput($request->except('g-recaptcha-response'));
        }

        // Cek apakah email sudah mengirim pesan hari ini
        if (Contact::hasSentToday($sanitized['email'])) {
            return redirect()->route('contact')
                ->with('error', 'Email ini sudah mengirim pesan hari ini. Silakan coba lagi besok.')
                ->withInput($request->except('g-recaptcha-response'));
        }

        try {
            // Email selalu dikirim ke tabaniakmal@gmail.com
            $adminEmail = 'tabaniakmal@gmail.com';

            Mail::to($adminEmail)->send(new ContactMail(
                $sanitized['name'],
                $sanitized['email'],
                $sanitized['subject'],
                $sanitized['message']
            ));

            // Simpan ke database untuk tracking
            Contact::create([
                'name' => $sanitized['name'],
                'email' => $sanitized['email'],
                'subject' => $sanitized['subject'],
                'message' => $sanitized['message'],
                'ip_address' => $request->ip(),
                'sent_at' => now(),
            ]);

            return redirect()->route('contact')->with('success', 'Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Contact form error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except('g-recaptcha-response')
            ]);
            
            // Untuk development, tampilkan error detail
            // Untuk production, gunakan pesan umum
            $errorMessage = app()->environment('local') 
                ? 'Error: ' . $e->getMessage() 
                : 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi atau hubungi kami langsung.';
            
            return redirect()->route('contact')->with('error', $errorMessage)
                ->withInput($request->except('g-recaptcha-response'));
        }
    }

    /**
     * Sanitasi input untuk mencegah XSS dan injection
     */
    private function sanitizeInput($input): string
    {
        if (!is_string($input)) {
            return '';
        }

        // Hapus whitespace di awal dan akhir
        $input = trim($input);
        
        // Hapus karakter kontrol kecuali newline dan tab
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Escape HTML entities (akan di-decode saat ditampilkan jika perlu)
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8', false);
        
        return $input;
    }

    /**
     * Verifikasi Google reCAPTCHA
     */
    private function verifyRecaptcha(string $recaptchaResponse, string $ip): array
    {
        $secretKey = config('services.recaptcha.secret_key');
        
        if (empty($secretKey)) {
            // Jika secret key tidak di-set, skip validasi (untuk development)
            return ['success' => true];
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $ip,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Verification failed'];
        }
    }
}
