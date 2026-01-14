<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use App\Models\Testimonial;
class AboutController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('about', compact('testimonials'));
    }

    public function show($id)
    {
        $About = About ::findOrFail($id);
        return view('template.about', compact('About'));
    }
}
