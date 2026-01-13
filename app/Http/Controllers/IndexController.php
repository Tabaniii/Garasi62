<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        // Get cars data for display (latest cars, limit to 12 for homepage)
        $cars = car::orderBy('created_at', 'desc')->limit(12)->get();
        
        // Get unique values for filters from database
        $brands = car::distinct()->whereNotNull('brand')->pluck('brand')->sort()->values();
        $tahunList = car::distinct()->whereNotNull('tahun')->pluck('tahun')->sort()->values();
        $transmisiList = car::distinct()->whereNotNull('transmisi')->pluck('transmisi')->sort()->values();
        $kapasitasmesinList = car::distinct()->whereNotNull('kapasitasmesin')->pluck('kapasitasmesin')->sort()->values();
        
        // Get min and max price from database
        $minPrice = car::whereNotNull('harga')->min(DB::raw('CAST(harga AS UNSIGNED)')) ?? 0;
        $maxPrice = car::whereNotNull('harga')->max(DB::raw('CAST(harga AS UNSIGNED)')) ?? 1000000000;

        return view('index', compact('cars', 'brands', 'tahunList', 'transmisiList', 'kapasitasmesinList', 'minPrice', 'maxPrice'));
    }
}
