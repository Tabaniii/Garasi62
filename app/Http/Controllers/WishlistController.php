<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\car;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Add car to wishlist
     */
    public function store(Request $request, $carId)
    {
        $user = Auth::user();

        // Check if user is buyer
        if ($user->role !== 'buyer') {
            return back()->with('error', 'Hanya buyer yang dapat menambahkan mobil ke wishlist.');
        }

        // Check if car exists and is approved
        $car = car::where('status', 'approved')->findOrFail($carId);

        // Check if already in wishlist
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('car_id', $carId)
            ->first();

        if ($existingWishlist) {
            return back()->with('info', 'Mobil sudah ada di wishlist Anda.');
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'car_id' => $carId,
        ]);

        return back()->with('success', 'Mobil berhasil ditambahkan ke wishlist!');
    }

    /**
     * Remove car from wishlist
     */
    public function destroy($carId)
    {
        $user = Auth::user();

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('car_id', $carId)
            ->firstOrFail();

        $wishlist->delete();

        return back()->with('success', 'Mobil berhasil dihapus dari wishlist!');
    }
}
