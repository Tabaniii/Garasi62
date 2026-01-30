<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|max:15',
            'gender' => 'required',
            'city' => 'required',
            'institution' => 'required',
            'role' => 'required|in:buyer,seller',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required' 
        ], [
            'role.required' => 'Pilih tipe akun (Buyer atau Seller).',
            'role.in' => 'Tipe akun harus Buyer atau Seller.',
        ]);

        // Generate kode verifikasi 6 digit
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Simpan data user sementara di session (belum dibuat di database)
        Session::put('pending_user', [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'city' => $request->city,
            'institution' => $request->institution,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode,
            'code_expires_at' => Carbon::now()->addMinutes(60),
        ]);

        // Kirim email dengan kode verifikasi
        try {
            Mail::to($request->email)->send(new VerificationCodeMail($request->name, $verificationCode));
            
            return redirect()->route('register.verify')->with('success', 'Kode verifikasi telah dikirim ke email Anda. Silakan cek inbox atau spam folder.');
        } catch (\Exception $e) {
            Session::forget('pending_user');
            return back()->withErrors(['email' => 'Gagal mengirim email. Pastikan email Anda valid.'])->withInput();
        }
    }

    public function showVerifyForm()
    {
        if (!Session::has('pending_user')) {
            return redirect()->route('register')->with('error', 'Sesi registrasi telah berakhir. Silakan daftar ulang.');
        }

        return view('auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ], [
            'verification_code.required' => 'Kode verifikasi wajib diisi.',
            'verification_code.size' => 'Kode verifikasi harus 6 digit.',
        ]);

        if (!Session::has('pending_user')) {
            return redirect()->route('register')->with('error', 'Sesi registrasi telah berakhir. Silakan daftar ulang.');
        }

        $pendingUser = Session::get('pending_user');

        // Cek apakah kode sudah expired
        if (Carbon::parse($pendingUser['code_expires_at'])->isPast()) {
            Session::forget('pending_user');
            return redirect()->route('register')->with('error', 'Kode verifikasi telah kedaluwarsa. Silakan daftar ulang.');
        }

        // Cek apakah kode benar
        if ($request->verification_code !== $pendingUser['verification_code']) {
            return back()->withErrors(['verification_code' => 'Kode verifikasi tidak valid.'])->withInput();
        }

        // Buat user di database
        $user = Users::create([
            'name' => $pendingUser['name'],
            'email' => $pendingUser['email'],
            'phone' => $pendingUser['phone'],
            'gender' => $pendingUser['gender'],
            'city' => $pendingUser['city'],
            'institution' => $pendingUser['institution'],
            'role' => $pendingUser['role'],
            'password' => $pendingUser['password'],
            'email_verified_at' => Carbon::now(),
        ]);

        // Hapus session pending user
        Session::forget('pending_user');

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Email Anda telah terverifikasi. Silakan login.');
    }

    public function resendCode()
    {
        if (!Session::has('pending_user')) {
            return redirect()->route('register')->with('error', 'Sesi registrasi telah berakhir. Silakan daftar ulang.');
        }

        $pendingUser = Session::get('pending_user');

        // Generate kode baru
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update session dengan kode baru
        $pendingUser['verification_code'] = $verificationCode;
        $pendingUser['code_expires_at'] = Carbon::now()->addMinutes(10);
        Session::put('pending_user', $pendingUser);

        // Kirim email dengan kode baru
        try {
            Mail::to($pendingUser['email'])->send(new VerificationCodeMail($pendingUser['name'], $verificationCode));
            
            return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim email. Silakan coba lagi.']);
        }
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Users::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput($request->only('email'));
        }

        // Cek apakah email sudah terverifikasi
        if (!$user->email_verified_at) {
            return back()->withErrors(['email' => 'Email belum terverifikasi. Silakan verifikasi email Anda terlebih dahulu.'])->withInput($request->only('email'));
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            // Redirect ke halaman yang diminta sebelumnya atau dashboard
            $intended = $request->session()->pull('url.intended', route('dashboard'));
            return redirect($intended)->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput($request->only('email'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
