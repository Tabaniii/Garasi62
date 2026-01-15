<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\car;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display cart page (Buyer only)
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is buyer
        if ($user->role !== 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Hanya buyer yang dapat mengakses keranjang.');
        }

        $carts = Cart::where('buyer_id', $user->id)
            ->with('car')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate total
        $total = 0;
        foreach ($carts as $cart) {
            if ($cart->car && $cart->car->harga) {
                $harga = (int) str_replace(['.', ','], '', $cart->car->harga);
                $total += $harga * $cart->quantity;
            }
        }

        return view('cart.index', compact('carts', 'total'));
    }

    /**
     * Add car to cart (Buyer only)
     */
    public function store(Request $request, $carId)
    {
        $user = Auth::user();

        // Check if user is buyer
        if ($user->role !== 'buyer') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya buyer yang dapat menambahkan mobil ke keranjang.'
                ], 403);
            }
            return back()->with('error', 'Hanya buyer yang dapat menambahkan mobil ke keranjang.');
        }

        // Check if car exists and is approved
        $car = car::where('status', 'approved')->findOrFail($carId);

        // Check if already in cart
        $existingCart = Cart::where('buyer_id', $user->id)
            ->where('car_id', $carId)
            ->first();

        if ($existingCart) {
            // Update quantity
            $existingCart->quantity += $request->input('quantity', 1);
            $existingCart->save();
            
            $message = 'Jumlah mobil di keranjang berhasil diperbarui.';
        } else {
            // Create new cart item
            Cart::create([
                'buyer_id' => $user->id,
                'car_id' => $carId,
                'quantity' => $request->input('quantity', 1),
            ]);
            
            $message = 'Mobil berhasil ditambahkan ke keranjang!';
        }

        // Get cart count for badge update
        $cartCount = Cart::where('buyer_id', $user->id)->count();

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cartCount
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $cart = Cart::where('buyer_id', $user->id)->findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cart->quantity = $request->quantity;
        $cart->save();

        return back()->with('success', 'Jumlah berhasil diperbarui.');
    }

    /**
     * Remove car from cart
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $cart = Cart::where('buyer_id', $user->id)->findOrFail($id);
        $cart->delete();

        return back()->with('success', 'Mobil berhasil dihapus dari keranjang.');
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        $user = Auth::user();

        Cart::where('buyer_id', $user->id)->delete();

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
