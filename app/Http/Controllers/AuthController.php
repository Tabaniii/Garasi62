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
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required' 
        ]);

        Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'city' => $request->city,
            'institution' => $request->institution,
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
            return redirect()->route('dashboard')->with('success', 'Selamat datang di dashboard!'); 
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}