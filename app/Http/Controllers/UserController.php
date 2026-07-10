<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\car;
use App\Models\CarApproval;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Report;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $users = Users::withCount('cars')->orderBy('created_at', 'desc')->paginate(10);
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
                'cars as approved_cars' => function ($query) {
                    $query->where('status', 'approved');
                },
                'cars as pending_cars' => function ($query) {
                    $query->where('status', 'pending');
                },
                'cars as rejected_cars' => function ($query) {
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

        // Task 1: Admin tidak bisa mengubah role nya sendiri
        $isSelfAdmin = ($user->id === Auth::id() && $user->role === 'admin');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|max:15',
            'gender' => 'required|in:Perempuan,Laki-laki',
            'city' => 'required|string|max:255',
            'institution' => 'required|in:Perorangan,Dealer',
            'password' => 'nullable|min:6|confirmed',
        ];

        // Jika bukan self-admin, role boleh diubah (tapi tetap divalidasi)
        if (!$isSelfAdmin) {
            $rules['role'] = 'required|in:admin,buyer,seller';
        }

        $request->validate($rules, [
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
        ];

        // Hanya update role jika bukan self-admin
        if (!$isSelfAdmin) {
            $data['role'] = $request->role;
        }

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

        try {
            DB::beginTransaction();

            // Task 2: Hapus semua yang berkaitan dengan akun tersebut

            // 1. Hapus Cars dan yang berkaitan (Approvals, Wishlists, Carts, Reports)
            $carIds = car::where('seller_id', $user->id)->pluck('id');

            if ($carIds->count() > 0) {
                CarApproval::whereIn('car_id', $carIds)->delete();
                Wishlist::whereIn('car_id', $carIds)->delete();
                Cart::whereIn('car_id', $carIds)->delete();
                Report::whereIn('car_id', $carIds)->delete();

                // Hapus file gambar mobil jika ada
                foreach (car::whereIn('id', $carIds)->get() as $car) {
                    if ($car->image && is_array($car->image)) {
                        foreach ($car->image as $img) {
                            $filePath = public_path($img);
                            if (file_exists($filePath)) {
                                @unlink($filePath);
                            }
                        }
                    }
                }

                car::whereIn('id', $carIds)->delete();
            }

            // 2. Hapus Wishlist & Cart milik user ini (sebagai buyer)
            Wishlist::where('user_id', $user->id)->delete();
            Cart::where('buyer_id', $user->id)->delete();

            // 3. Hapus Chat dan Message
            $chats = Chat::where('buyer_id', $user->id)
                ->orWhere('seller_id', $user->id)
                ->get();

            foreach ($chats as $chat) {
                Message::where('chat_id', $chat->id)->delete();
                $chat->delete();
            }

            // 4. Hapus Reports yang dibuat oleh user ini
            Report::where('reporter_id', $user->id)->delete();

            // Akhirnya hapus usernya
            $user->delete();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User dan semua data terkait berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
