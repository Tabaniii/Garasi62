<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
class AboutController extends Controller
{
    public function show($id)
    {
        $About = About ::findOrFail($id);
        return view('template.about', compact('About'));
    }
}
