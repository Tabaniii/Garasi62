<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function show(Request $request)
    {
        $query = car::where('status', 'approved');

        // Search by nama or brand
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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
            $query->whereRaw('CAST(harga AS UNSIGNED) >= ?', [$request->min_price]);
        }
        if ($request->filled('max_price')) {
            $query->whereRaw('CAST(harga AS UNSIGNED) <= ?', [$request->max_price]);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy == 'harga') {
            $query->orderByRaw('CAST(harga AS UNSIGNED) ' . $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->get('per_page', 9);
        $cars = $query->paginate($perPage)->withQueryString();

        // Get unique values for filters from database
        $brands = car::distinct()->whereNotNull('brand')->pluck('brand')->sort()->values();
        $transmisiList = car::distinct()->whereNotNull('transmisi')->pluck('transmisi')->sort()->values();
        $tahunList = car::distinct()->whereNotNull('tahun')->pluck('tahun')->sort()->values();
        $metodeList = car::distinct()->whereNotNull('metode')->pluck('metode')->sort()->values();
        $kapasitasmesinList = car::distinct()->whereNotNull('kapasitasmesin')->pluck('kapasitasmesin')->sort()->values();
        
        // Get min and max price from database
        $minPrice = car::whereNotNull('harga')->min(DB::raw('CAST(harga AS UNSIGNED)'));
        $maxPrice = car::whereNotNull('harga')->max(DB::raw('CAST(harga AS UNSIGNED)'));

        return view('car', compact('cars', 'brands', 'transmisiList', 'tahunList', 'metodeList', 'kapasitasmesinList', 'minPrice', 'maxPrice'));
    }

    // CRUD Methods untuk Dashboard
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin can see all cars with status info
            $cars = car::with('seller')->orderBy('created_at', 'desc')->get();
        } elseif ($user->role === 'seller') {
            // Seller can only see their own cars
            $cars = car::where('seller_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            // Buyer cannot access this page
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('cars.index', compact('cars'));
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
                'harga' => 'required|string|max:10',
                'metode' => 'required|string|max:5',
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

            // Ensure storage directory exists
            if (!Storage::disk('public')->exists('cars')) {
                Storage::disk('public')->makeDirectory('cars');
            }

            $imagePaths = [];
            foreach ($files as $file) {
                // Generate hash dari file untuk cek duplikat
                $fileHash = hash_file('md5', $file->getRealPath());
                
                // Cek apakah file dengan hash yang sama sudah ada
                $existingCar = car::whereNotNull('image')
                    ->get()
                    ->filter(function($car) use ($fileHash) {
                        if (is_array($car->image)) {
                            foreach ($car->image as $imagePath) {
                                $fullPath = storage_path('app/public/' . $imagePath);
                                if (file_exists($fullPath) && hash_file('md5', $fullPath) === $fileHash) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    })
                    ->first();
                
                if ($existingCar) {
                    // Gunakan path yang sudah ada
                    $existingImagePath = null;
                    if (is_array($existingCar->image)) {
                        foreach ($existingCar->image as $imagePath) {
                            $fullPath = storage_path('app/public/' . $imagePath);
                            if (file_exists($fullPath) && hash_file('md5', $fullPath) === $fileHash) {
                                $existingImagePath = $imagePath;
                                break;
                            }
                        }
                    }
                    if ($existingImagePath) {
                        $imagePaths[] = $existingImagePath;
                        continue; // Skip upload, gunakan yang sudah ada
                    }
                }
                
                // Upload file baru jika tidak ada duplikat
                $path = $file->store('cars', 'public');
                if (!$path) {
                    throw new \Exception('Gagal mengupload gambar. Pastikan folder storage dapat ditulis.');
                }
                $imagePaths[] = $path;
            }

            $data = $request->except(['images']);
            $data['image'] = $imagePaths;

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

            car::create($data);

            return redirect()->route('cars.index')->with('success', 'Mobil berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing car: ' . $e->getMessage());
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
            if ($car->seller_id !== $user->id) {
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
            'tipe' => 'required|in:rent,buy',
            'tahun' => 'required|string|max:4',
            'brand' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'kilometer' => 'required|string|max:6',
            'transmisi' => 'required|string|max:10',
            'harga' => 'required|string|max:10',
            'metode' => 'required|string|max:5',
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
            if ($car->seller_id !== $user->id) {
                return redirect()->route('cars.index')->with('error', 'Anda tidak memiliki akses untuk mengupdate mobil ini.');
            }
        } else {
            // Buyer cannot update
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Handle existing images
        $existingImages = $request->input('existing_images', []);
        $imagePaths = $existingImages;

        // Handle new uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate hash dari file untuk cek duplikat
                $fileHash = hash_file('md5', $image->getRealPath());
                
                // Cek apakah file dengan hash yang sama sudah ada
                $existingCar = car::where('id', '!=', $id)
                    ->whereNotNull('image')
                    ->get()
                    ->filter(function($otherCar) use ($fileHash) {
                        if (is_array($otherCar->image)) {
                            foreach ($otherCar->image as $imagePath) {
                                $fullPath = storage_path('app/public/' . $imagePath);
                                if (file_exists($fullPath) && hash_file('md5', $fullPath) === $fileHash) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    })
                    ->first();
                
                if ($existingCar) {
                    // Gunakan path yang sudah ada
                    $existingImagePath = null;
                    if (is_array($existingCar->image)) {
                        foreach ($existingCar->image as $imagePath) {
                            $fullPath = storage_path('app/public/' . $imagePath);
                            if (file_exists($fullPath) && hash_file('md5', $fullPath) === $fileHash) {
                                $existingImagePath = $imagePath;
                                break;
                            }
                        }
                    }
                    if ($existingImagePath) {
                        $imagePaths[] = $existingImagePath;
                        continue; // Skip upload, gunakan yang sudah ada
                    }
                }
                
                // Upload file baru jika tidak ada duplikat
                $path = $image->store('cars', 'public');
                $imagePaths[] = $path;
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

        $data = $request->except(['images', 'existing_images']);
        $data['image'] = $imagePaths;

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

        $car->update($data);

        return redirect()->route('cars.index')->with('success', 'Mobil berhasil diperbarui!');
    }

    public function showDetail($id)
    {
        $car = car::where('status', 'approved')->findOrFail($id);
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
                if ($car->seller_id !== $user->id) {
                    return redirect()->route('cars.index')->with('error', 'Anda tidak memiliki akses untuk menghapus mobil ini.');
                }
            } else {
                // Buyer cannot delete
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
            
            // Hapus gambar dari storage
            if ($car->image && is_array($car->image)) {
                foreach ($car->image as $imagePath) {
                    // Cek apakah gambar digunakan oleh mobil lain
                    $isUsedByOtherCar = car::where('id', '!=', $id)
                        ->whereNotNull('image')
                        ->get()
                        ->filter(function($otherCar) use ($imagePath) {
                            if (is_array($otherCar->image)) {
                                return in_array($imagePath, $otherCar->image);
                            }
                            return false;
                        })
                        ->isNotEmpty();
                    
                    // Hapus file hanya jika tidak digunakan oleh mobil lain
                    if (!$isUsedByOtherCar && Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
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
            \Log::error('Error deleting car: ' . $e->getMessage());
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus mobil.'
                ], 500);
            }
            
            return redirect()->route('cars.index')->with('error', 'Terjadi kesalahan saat menghapus mobil.');
        }
    }
}
