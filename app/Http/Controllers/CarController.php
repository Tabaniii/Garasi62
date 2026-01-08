<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Check if image already exists by hash
     */
    private function findExistingImage($file)
    {
        $hash = md5_file($file->getRealPath());
        $allCars = car::whereNotNull('image')->get();
        
        foreach ($allCars as $car) {
            if ($car->image && is_array($car->image)) {
                foreach ($car->image as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        $existingHash = md5_file(Storage::disk('public')->path($imagePath));
                        if ($existingHash === $hash) {
                            return $imagePath;
                        }
                    }
                }
            }
        }
        
        return null;
    }

    /**
     * Delete unused images
     */
    private function deleteUnusedImages($oldImages, $newImages, $currentCarId = null)
    {
        $imagesToDelete = array_diff($oldImages, $newImages);
        
        foreach ($imagesToDelete as $imagePath) {
            // Check if image is used by other cars
            $query = car::whereJsonContains('image', $imagePath);
            if ($currentCarId) {
                $query->where('id', '!=', $currentCarId);
            }
            $isUsed = $query->exists();
            
            if (!$isUsed && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
    }
    public function show()
    {
        $cars = car::all();
        return view('car', compact('cars'));
    }

    // CRUD Methods untuk Dashboard
    public function index()
    {
        $cars = car::orderBy('created_at', 'desc')->get();
        return view('cars.index', compact('cars'));
    }

    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1|max:6',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // max 5MB per image
            'tipe' => 'required|in:rent,buy',
            'tahun' => 'required|string|max:4',
            'brand' => 'required|string|max:20',
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

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Check if image already exists by hash
                $existingPath = $this->findExistingImage($image);
                
                if ($existingPath) {
                    // Use existing image (no duplicate upload)
                    $imagePaths[] = $existingPath;
                } else {
                    // Upload new image
                    $path = $image->store('cars', 'public');
                    $imagePaths[] = $path;
                }
            }
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

        car::create($data);

        return redirect()->route('cars.index')->with('success', 'Mobil berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $car = car::findOrFail($id);
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
        
        // Get old images for cleanup
        $oldImages = $car->image && is_array($car->image) ? $car->image : [];
        
        // Handle existing images
        $existingImages = $request->input('existing_images', []);
        $imagePaths = $existingImages;

        // Handle new uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Check if image already exists by hash
                $existingPath = $this->findExistingImage($image);
                
                if ($existingPath) {
                    // Use existing image (no duplicate upload)
                    $imagePaths[] = $existingPath;
                } else {
                    // Upload new image
                    $path = $image->store('cars', 'public');
                    $imagePaths[] = $path;
                }
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
        $data['image'] = array_unique($imagePaths); // Remove duplicates

        // Delete unused images (only if not used by other cars)
        $this->deleteUnusedImages($oldImages, $data['image'], $id);

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
        $car = car::findOrFail($id);
        return view('car-details', compact('car'));
    }

    public function destroy($id)
    {
        $car = car::findOrFail($id);
        
        // Get images before delete
        $imagesToDelete = $car->image && is_array($car->image) ? $car->image : [];
        
        // Delete car first
        $car->delete();
        
        // Hapus gambar dari storage jika tidak digunakan mobil lain
        foreach ($imagesToDelete as $imagePath) {
            // Check if image is used by other cars
            $isUsed = car::whereJsonContains('image', $imagePath)->exists();
            
            if (!$isUsed && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        return redirect()->route('cars.index')->with('success', 'Mobil berhasil dihapus!');
    }
}
