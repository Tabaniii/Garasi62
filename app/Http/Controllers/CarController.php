<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;

class CarController extends Controller
{
    public function show()
    {
        $cars = car::all();
        return view('car', compact('cars'));
    }

}
