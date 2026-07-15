<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CarApproval;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function show(Request $request)
    {
        $query = car::where('status', 'approved');

        // Search by nama or brand
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('brand', 'like', '%' . $search . '%');
            });
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by transmisi
        if ($request->filled('transmisi')) {
            $query->where('transmisi', $request->transmisi);
        }

        // Filter by bahan bakar
        if ($request->filled('bahan_bakar')) {
            $query->where('bahan_bakar', $request->bahan_bakar);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by tipe (rent/buy)
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter by metode
        if ($request->filled('metode')) {
            $query->where('metode', $request->metode);
        }

        // Filter by kapasitas mesin
        if ($request->filled('kapasitasmesin')) {
            $query->where('kapasitasmesin', $request->kapasitasmesin);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->whereRaw('CAST(harga AS BIGINT) >= ?', [$request->min_price]);
        }
        if ($request->filled('max_price')) {
            $query->whereRaw('CAST(harga AS BIGINT) <= ?', [$request->max_price]);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy == 'harga') {
            $query->orderByRaw('CAST(harga AS BIGINT) ' . $sortOrder);
        } elseif ($sortBy == 'tahun') {
            $query->orderBy('tahun', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->get('per_page', 9);
        $cars = $query->paginate($perPage)->withQueryString();

        // Get unique values for filters from database
        $brands = car::distinct()->whereNotNull('brand')->pluck('brand')->sort()->values();
        $transmisiList = car::distinct()->whereNotNull('transmisi')->pluck('transmisi')->sort()->values();
        $bahanBakarList = car::distinct()->whereNotNull('bahan_bakar')->pluck('bahan_bakar')->sort()->values();
        $locationList = car::distinct()->whereNotNull('location')->pluck('location')->sort()->values();
        $tahunList = car::distinct()->whereNotNull('tahun')->pluck('tahun')->sort()->values();
        $metodeList = car::distinct()->whereNotNull('metode')->pluck('metode')->sort()->values();
        $kapasitasmesinList = car::distinct()->whereNotNull('kapasitasmesin')->pluck('kapasitasmesin')->sort()->values();

        // Get min and max price from database
        $minPrice = car::whereNotNull('harga')->min(DB::raw('CAST(harga AS BIGINT)'));
        $maxPrice = car::whereNotNull('harga')->max(DB::raw('CAST(harga AS BIGINT)'));

        return view('car', compact('cars', 'brands', 'transmisiList', 'bahanBakarList', 'locationList', 'tahunList', 'metodeList', 'kapasitasmesinList', 'minPrice', 'maxPrice'));
    }

    // CRUD Methods untuk Dashboard
    public function index(Request $request)
    {
        $user = Auth::user();

        // Query builder dengan select hanya kolom yang diperlukan untuk performa lebih baik
        $query = car::select([
            'id',
            'nama',
            'brand',
            'tahun',
            'kilometer',
            'transmisi',
            'bahan_bakar',
            'kapasitasmesin',
            'harga',
            'metode',
            'tipe',
            'image',
            'is_foto_duplikat',
            'status',
            'seller_id',
            'location',
            'created_at',
            'updated_at'
        ]);

        if ($user->role === 'admin') {
            // Admin can see all cars with status info
            // Filter by seller_id if provided
            if ($request->has('seller_id') && $request->seller_id) {
                $query->where('seller_id', $request->seller_id);
            }
            // Filter by Area/Kota
            if ($request->filled('location')) {
                $query->where('location', $request->location);
            }
            // Eager load seller for admin view
            $query->with('seller');
        } elseif ($user->role === 'seller') {
            // Seller can only see their own cars
            $query->where('seller_id', $user->id);
            // Filter by Area/Kota
            if ($request->filled('location')) {
                $query->where('location', $request->location);
            }
            // Eager load reports that caused unpublish (resolved with admin_notes)
            $query->with([
                'reports' => function ($q) {
                    $q->where('status', 'resolved')
                        ->whereNotNull('admin_notes')
                        ->latest();
                },
                // Eager load rejection approvals to show reason and date
                'approvals' => function ($q) {
                    $q->where('action', 'rejected')->latest();
                }
            ]);
        } else {
            // Buyer cannot access this page
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination - hanya load 12 item per halaman untuk performa lebih baik
        $perPage = $request->get('per_page', 12);
        $cars = $query->paginate($perPage)->withQueryString();

        // Daftar Area/Kota untuk filter (admin: semua, seller: mobil mereka saja)
        $locationList = $user->role === 'admin'
            ? car::distinct()->whereNotNull('location')->where('location', '!=', '')->pluck('location')->sort()->values()
            : car::where('seller_id', $user->id)->distinct()->whereNotNull('location')->where('location', '!=', '')->pluck('location')->sort()->values();

        return view('cars.index', compact('cars', 'locationList'));
    }

    public function create()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'seller'])) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('cars.create');
    }

    public function store(Request $request)
    {
        try {
            // Validasi file terlebih dahulu
            if (!$request->hasFile('images')) {
                return back()->withErrors(['images' => 'Gambar wajib diisi.'])->withInput();
            }

            $files = $request->file('images');
            if (empty($files) || count($files) < 1) {
                return back()->withErrors(['images' => 'Minimal 1 gambar diperlukan.'])->withInput();
            }

            if (count($files) > 6) {
                return back()->withErrors(['images' => 'Maksimal 6 gambar yang diizinkan.'])->withInput();
            }

            // Validasi setiap file
            foreach ($files as $index => $file) {
                if (!$file->isValid()) {
                    return back()->withErrors(['images.' . $index => 'File gambar tidak valid.'])->withInput();
                }

                // Cek MIME type
                $mimeType = $file->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'];

                if (!in_array($mimeType, $allowedMimes)) {
                    return back()->withErrors(['images.' . $index => 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.'])->withInput();
                }

                // Cek ukuran file (5MB = 5120 KB)
                if ($file->getSize() > 5120 * 1024) {
                    return back()->withErrors(['images.' . $index => 'Ukuran file terlalu besar. Maksimal 5MB per gambar.'])->withInput();
                }
            }

            // Validasi field lainnya
            $request->validate([
                'tipe' => 'required|in:rent,buy',
                'tahun' => 'required|string|max:4',
                'brand' => 'required|string|max:20',
                'nama' => 'required|string|max:100',
                'kilometer' => 'required|string|max:6',
                'transmisi' => 'required|string|max:10',
                'bahan_bakar' => 'nullable|string|max:50',
                'harga' => 'required|string|max:10',
                'metode' => 'required|string|max:10',
                'kapasitasmesin' => 'required|string|max:50',
                'stock' => 'nullable|string|max:50',
                'vin' => 'nullable|string|max:50',
                'msrp' => 'nullable|string|max:15',
                'dealer_discounts' => 'nullable|string|max:15',
                'description' => 'nullable|string',
                'interior_features' => 'nullable|array',
                'safety_features' => 'nullable|array',
                'extra_features' => 'nullable|array',
                'technical_specs' => 'nullable|string',
                'location' => 'nullable|string|max:255',
            ]);

            $imagePaths = [];
            $adaDuplikat = false;
            foreach ($files as $file) {
                $fileHash = media()->hashUploadedFile($file);
                $existingImagePath = media()->findExistingImageByHash($fileHash);

                if ($existingImagePath) {
                    $adaDuplikat = true;
                    $imagePaths[] = $existingImagePath;
                    continue;
                }

                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = \Illuminate\Support\Str::slug($originalName, '_');
                $extension = $file->getClientOriginalExtension();
                $newFileName = 'ride62_' . $safeName . '_' . uniqid() . '.' . $extension;
                $imagePaths[] = media()->uploadAs($file, 'cars', $newFileName);
            }

            $data = $request->except(['images']);
            $data['image'] = $imagePaths;
            $data['is_foto_duplikat'] = $adaDuplikat;

            // Handle features arrays
            if ($request->has('interior_features')) {
                $data['interior_features'] = array_filter($request->input('interior_features', []));
            }
            if ($request->has('safety_features')) {
                $data['safety_features'] = array_filter($request->input('safety_features', []));
            }
            if ($request->has('extra_features')) {
                $data['extra_features'] = array_filter($request->input('extra_features', []));
            }

            // Add seller_id and status for approval system
            $data['seller_id'] = Auth::id();
            $data['status'] = 'pending';

            // Pastikan bahan_bakar ikut tersimpan
            $data['bahan_bakar'] = $request->input('bahan_bakar') ?: null;

            car::create($data);

            return redirect()->route('cars.index')->with('success', 'Mobil berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing car: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $car = car::findOrFail($id);

        // Check permissions
        if ($user->role === 'admin') {
            // Admin can edit all cars
        } elseif ($user->role === 'seller') {
            // Seller can only edit their own cars
            if ((int) $car->seller_id !== (int) $user->id) {
                return redirect()->route('cars.index')->with('error', 'Anda tidak memiliki akses untuk mengedit mobil ini.');
            }
        } else {
            // Buyer cannot edit
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // max 5MB per image
            'existing_images' => 'nullable|array',
            'removed_images' => 'nullable|array',
            'tipe' => 'required|in:rent,buy',
            'tahun' => 'required|string|max:4',
            'brand' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'kilometer' => 'required|string|max:6',
            'transmisi' => 'required|string|max:10',
            'bahan_bakar' => 'nullable|string|max:50',
            'harga' => 'required|string|max:10',
            // Izinkan nilai seperti "Cash" atau "Kredit" (<= 10 karakter)
            'metode' => 'required|string|max:10',
            'kapasitasmesin' => 'required|string|max:50',
            'stock' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:50',
            'msrp' => 'nullable|string|max:15',
            'dealer_discounts' => 'nullable|string|max:15',
            'description' => 'nullable|string',
            'interior_features' => 'nullable|array',
            'safety_features' => 'nullable|array',
            'extra_features' => 'nullable|array',
            'technical_specs' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        $car = car::findOrFail($id);

        // Check permissions
        $user = Auth::user();
        if ($user->role === 'admin') {
            // Admin can update all cars
        } elseif ($user->role === 'seller') {
            // Seller can only update their own cars
            if ((int) $car->seller_id !== (int) $user->id) {
                return redirect()->route('cars.index')->with('error', 'Anda tidak memiliki akses untuk mengupdate mobil ini.');
            }
        } else {
            // Buyer cannot update
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Handle existing images
        $existingImages = $request->input('existing_images', []);
        $removedImages = $request->input('removed_images', []);

        // Get current images
        $currentImages = $car->image ?? [];
        if (!is_array($currentImages))
            $currentImages = [];

        // Filter out removed images
        $imagePaths = array_filter($currentImages, function ($imagePath) use ($removedImages) {
            return !in_array($imagePath, $removedImages);
        });

        // Add back existing images that were kept
        $imagePaths = array_values($imagePaths);

        // Delete removed image files from storage
        foreach ($removedImages as $removedImage) {
            if (!media()->exists($removedImage)) {
                continue;
            }

            $usedByOtherCars = car::where('id', '!=', $id)
                ->whereNotNull('image')
                ->get()
                ->filter(function ($otherCar) use ($removedImage) {
                    $otherImages = $otherCar->image ?? [];
                    return in_array($removedImage, $otherImages);
                })
                ->count() > 0;

            if (!$usedByOtherCars) {
                media()->delete($removedImage);
            }
        }

        // Handle new uploaded images
        $adaDuplikat = false;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileHash = media()->hashUploadedFile($image);
                $existingImagePath = media()->findExistingImageByHash($fileHash, $id);

                if ($existingImagePath) {
                    $adaDuplikat = true;
                    $imagePaths[] = $existingImagePath;
                    continue;
                }

                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = \Illuminate\Support\Str::slug($originalName, '_');
                $extension = $image->getClientOriginalExtension();
                $newFileName = 'ride62_' . $safeName . '_' . uniqid() . '.' . $extension;
                $imagePaths[] = media()->uploadAs($image, 'cars', $newFileName);
            }
        }

        // Validasi total images tidak lebih dari 6
        if (count($imagePaths) > 6) {
            return back()->withErrors(['images' => 'Maksimal 6 gambar yang diizinkan.'])->withInput();
        }

        // Validasi minimal 1 gambar
        if (count($imagePaths) < 1) {
            return back()->withErrors(['images' => 'Minimal 1 gambar diperlukan.'])->withInput();
        }

        $data = $request->except(['images', 'existing_images', 'removed_images']);
        $data['image'] = $imagePaths;
        $data['is_foto_duplikat'] = $adaDuplikat;

        // Handle features arrays
        if ($request->has('interior_features')) {
            $data['interior_features'] = array_filter($request->input('interior_features', []));
        }
        if ($request->has('safety_features')) {
            $data['safety_features'] = array_filter($request->input('safety_features', []));
        }
        if ($request->has('extra_features')) {
            $data['extra_features'] = array_filter($request->input('extra_features', []));
        }

        // Pastikan bahan_bakar ikut tersimpan
        $data['bahan_bakar'] = $request->input('bahan_bakar') ?: null;

        $car->update($data);

        return redirect()->route('cars.index')->with('success', 'Mobil berhasil diperbarui!');
    }

    public function showDetail($uuid)
    {
        $car = car::where('status', 'approved')->where('uuid', $uuid)->with('seller')->firstOrFail();
        return view('car-details', compact('car'));
    }

    public function destroy($id)
    {
        try {
            $car = car::findOrFail($id);

            // Check permissions
            $user = Auth::user();
            if ($user->role === 'admin') {
                // Admin can delete all cars
            } elseif ($user->role === 'seller') {
                // Seller can only delete their own cars
                if ((int) $car->seller_id !== (int) $user->id) {
                    return redirect()->route('cars.index')->with('error', 'Anda tidak memiliki akses untuk menghapus mobil ini.');
                }
            } else {
                // Buyer cannot delete
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }

            // Hapus gambar dari storage
            if ($car->image) {
                $images = $car->image;

                foreach ($images as $imagePath) {
                    // Cek apakah gambar digunakan oleh mobil lain
                    $isUsedByOtherCar = car::where('id', '!=', $id)
                        ->whereNotNull('image')
                        ->get()
                        ->filter(function ($otherCar) use ($imagePath) {
                            $otherImages = $otherCar->image ?? [];
                            return in_array($imagePath, $otherImages);
                        })
                        ->isNotEmpty();

                    // Hapus file hanya jika tidak digunakan oleh mobil lain
                    if (!$isUsedByOtherCar) {
                        media()->delete($imagePath);
                    }
                }
            }

            $car->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mobil berhasil dihapus!'
                ]);
            }

            return redirect()->route('cars.index')->with('success', 'Mobil berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting car: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus mobil.'
                ], 500);
            }

            return redirect()->route('cars.index')->with('error', 'Terjadi kesalahan saat menghapus mobil.');
        }
    }

    /**
     * Seller: List rejected cars and allow resubmission
     */
    public function sellerResubmissions(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'seller') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $cars = car::with([
            'approvals' => function ($q) {
                $q->orderBy('approved_at', 'desc');
            }
        ])
            ->where('seller_id', $user->id)
            ->where('status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('seller.resubmissions.index', compact('cars'));
    }

    /**
     * Seller: Resubmit a rejected car for approval
     * DINONAKTIFKAN - Pengajuan ulang tidak diizinkan
     */
    public function resubmit(Request $request, $id)
    {
        $car = car::where('seller_id', Auth::id())->findOrFail($id);

        if ($car->status !== 'rejected') {
            return redirect()->route('seller.resubmissions.index')
                ->with('error', 'Hanya mobil yang ditolak yang dapat diajukan ulang.');
        }

        // Update status to pending for re-approval
        $car->update([
            'status' => 'pending',
            'resubmitted_at' => now(),
        ]);

        return redirect()->route('seller.resubmissions.index')
            ->with('success', 'Mobil berhasil diajukan ulang untuk peninjauan.');
    }
}
