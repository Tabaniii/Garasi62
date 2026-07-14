<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use Illuminate\Support\Facades\DB;

class DuplicateCarController extends Controller
{
    public function index()
    {
        // ===== 1. Duplikat berdasarkan Nama, Brand, Tahun =====
        $duplicateGroups = car::select('nama', 'brand', 'tahun', DB::raw('count(*) as total'))
            ->whereNotNull('nama')
            ->groupBy('nama', 'brand', 'tahun')
            ->having('total', '>', 1)
            ->get();

        $duplicates = [];

        foreach ($duplicateGroups as $group) {
            $cars = car::with('seller')
                ->where('nama', $group->nama)
                ->where('brand', $group->brand)
                ->where('tahun', $group->tahun)
                ->orderBy('created_at', 'asc')
                ->get();

            $duplicates[] = [
                'group_info' => $group,
                'cars' => $cars
            ];
        }

        // ===== 2. Duplikat berdasarkan Gambar (hash MD5) =====
        $allCars = car::with('seller')->whereNotNull('image')->get();
        $imageHashMap = []; // hash => [car_id => path]
        $fotoDuplikatGroups = []; // hash => [cars]
        $fotoPencurianEntries = [];

        foreach ($allCars as $car) {
            if (!is_array($car->image))
                continue;
            foreach ($car->image as $imagePath) {
                $fileName = basename($imagePath);
                if (str_starts_with($fileName, 'garasi62_garasi62')) {
                    $fotoPencurianEntries[] = [
                        'car' => $car,
                        'image_path' => $imagePath,
                        'file_name' => $fileName,
                    ];
                }
                $hash = media()->hash($imagePath);
                if ($hash) {
                    if (!isset($imageHashMap[$hash])) {
                        $imageHashMap[$hash] = [];
                    }
                    $imageHashMap[$hash][] = [
                        'car' => $car,
                        'image_path' => $imagePath,
                        'file_name' => $fileName,
                    ];
                }
            }
        }

        // Hanya ambil yang punya duplikat (lebih dari 1 entry per hash)
        $fotoDuplikats = [];
        foreach ($imageHashMap as $hash => $entries) {
            if (count($entries) > 1) {
                $fotoDuplikats[] = [
                    'type' => 'hash',
                    'hash' => $hash,
                    'total' => count($entries),
                    'entries' => $entries,
                ];
            }
        }

        if (count($fotoPencurianEntries) > 0) {
            $fotoDuplikats[] = [
                'type' => 'prefix',
                'hash' => null,
                'total' => count($fotoPencurianEntries),
                'entries' => $fotoPencurianEntries,
            ];
        }

        return view('admin.duplicate-cars.index', compact('duplicates', 'fotoDuplikats'));
    }
}
