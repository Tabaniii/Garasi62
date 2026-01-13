<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Email selalu dikirim ke tabaniakmal@gmail.com
            $adminEmail = 'tabaniakmal@gmail.com';

            Mail::to($adminEmail)->send(new ContactMail(
                $request->name,
                $request->email,
                $request->subject,
                $request->message
            ));

            return redirect()->route('contact')->with('success', 'Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Contact form error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            // Untuk development, tampilkan error detail
            // Untuk production, gunakan pesan umum
            $errorMessage = app()->environment('local') 
                ? 'Error: ' . $e->getMessage() 
                : 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi atau hubungi kami langsung.';
            
            return redirect()->route('contact')->with('error', $errorMessage);
        }
    }
}
