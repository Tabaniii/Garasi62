<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\car;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya admin yang bisa akses
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
            }
            
            $user = Auth::user();
            
            if (!$user || $user->role !== 'admin') {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya admin yang bisa mengakses halaman ini.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = Users::orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * List all sellers (Admin only)
     */
    public function sellers()
    {
        $sellers = Users::where('role', 'seller')
            ->withCount([
                'cars as total_cars',
                'cars as approved_cars' => function($query) {
                    $query->where('status', 'approved');
                },
                'cars as pending_cars' => function($query) {
                    $query->where('status', 'pending');
                },
                'cars as rejected_cars' => function($query) {
                    $query->where('status', 'rejected');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.sellers.index', compact('sellers'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|max:15',
            'gender' => 'required|in:Perempuan,Laki-laki',
            'city' => 'required|string|max:255',
            'institution' => 'required|in:Perorangan,Dealer',
            'role' => 'required|in:admin,buyer,seller',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'city.required' => 'Kota wajib diisi.',
            'institution.required' => 'Institusi wajib dipilih.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role harus admin, buyer, atau seller.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'city' => $request->city,
            'institution' => $request->institution,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = Users::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Users::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|max:15',
            'gender' => 'required|in:Perempuan,Laki-laki',
            'city' => 'required|string|max:255',
            'institution' => 'required|in:Perorangan,Dealer',
            'role' => 'required|in:admin,buyer,seller',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'city.required' => 'Kota wajib diisi.',
            'institution.required' => 'Institusi wajib dipilih.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role harus admin, buyer, atau seller.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'city' => $request->city,
            'institution' => $request->institution,
            'role' => $request->role,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Users::findOrFail($id);

        // Jangan izinkan hapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}

