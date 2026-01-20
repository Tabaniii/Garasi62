<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use Illuminate\Support\Facades\DB;

class DuplicateCarController extends Controller
{
    public function index()
    {
        // Cari kombinasi nama, brand, dan tahun yang memiliki lebih dari 1 record
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

        return view('admin.duplicate-cars.index', compact('duplicates'));
    }
}
