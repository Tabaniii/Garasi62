<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'role' => 'required|in:buyer,seller', // Hanya buyer dan seller yang bisa register
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required' 
        ], [
            'role.required' => 'Pilih tipe akun (Buyer atau Seller).',
            'role.in' => 'Tipe akun harus Buyer atau Seller.',
        ]);

        Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'city' => $request->city,
            'institution' => $request->institution,
            'role' => $request->role, // buyer atau seller
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
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