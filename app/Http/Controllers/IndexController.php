<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        // Get cars data for display (latest approved cars, limit to 12 for homepage)
        $cars = car::where('status', 'approved')->orderBy('created_at', 'desc')->limit(12)->get();
        
        // Optimize: Fetch filter fields in one query to prevent multiple high-latency network round-trips to the remote database
        $filterData = car::where('status', 'approved')
            ->select('brand', 'tahun', 'transmisi', 'kapasitasmesin', 'harga')
            ->get();
            
        $brands = $filterData->pluck('brand')->filter()->unique()->sort()->values();
        $tahunList = $filterData->pluck('tahun')->filter()->unique()->sort()->values();
        $transmisiList = $filterData->pluck('transmisi')->filter()->unique()->sort()->values();
        $kapasitasmesinList = $filterData->pluck('kapasitasmesin')->filter()->unique()->sort()->values();
        
        // Parse prices to get min and max
        $prices = $filterData->pluck('harga')->filter()->map(function ($h) {
            return (int) $h;
        });
        
        $minPrice = $prices->min() ?? 0;
        $maxPrice = $prices->max() ?? 1000000000; 

        // Get latest published blogs (limit 3)
        $blogs = Blog::published()->orderBy('published_at', 'desc')->limit(3)->get();

        return view('index', compact('cars', 'brands', 'tahunList', 'transmisiList', 'kapasitasmesinList', 'minPrice', 'maxPrice', 'blogs'));
    }
}
