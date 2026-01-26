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
        // Get cars data for display (latest approved cars, limit to 4 for homepage)
        $cars = car::where('status', 'approved')->orderBy('created_at', 'desc')->limit(4)->get();
        
        // Get unique values for filters from database (only approved cars)
        $brands = car::where('status', 'approved')->distinct()->whereNotNull('brand')->pluck('brand')->sort()->values();
        $tahunList = car::where('status', 'approved')->distinct()->whereNotNull('tahun')->pluck('tahun')->sort()->values();
        $transmisiList = car::where('status', 'approved')->distinct()->whereNotNull('transmisi')->pluck('transmisi')->sort()->values();
        $kapasitasmesinList = car::where('status', 'approved')->distinct()->whereNotNull('kapasitasmesin')->pluck('kapasitasmesin')->sort()->values();
        
        // Get min and max price from database (only approved cars)
        $minPrice = car::where('status', 'approved')->whereNotNull('harga')->min(DB::raw('CAST(harga AS UNSIGNED)')) ?? 0;
        $maxPrice = car::where('status', 'approved')->whereNotNull('harga')->max(DB::raw('CAST(harga AS UNSIGNED)')) ?? 1000000000;

        // Get latest published blogs (limit 3)
        $blogs = Blog::published()->orderBy('published_at', 'desc')->limit(3)->get();

        return view('index', compact('cars', 'brands', 'tahunList', 'transmisiList', 'kapasitasmesinList', 'minPrice', 'maxPrice', 'blogs'));
    }
}
