<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use App\Models\User;
use App\Models\FundRequest;
use App\Models\Testimonial;
use App\Models\CarApproval;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect based on user role
        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'seller':
                return $this->sellerDashboard();
            case 'buyer':
                return $this->buyerDashboard();
            default:
                return $this->buyerDashboard(); // Default to buyer dashboard
        }
    }

    private function adminDashboard()
    {
        try {
            $totalFundRequests = FundRequest::count();
            $pendingFundRequests = FundRequest::where('status', 'pending')->count();
            $approvedFundRequests = FundRequest::where('status', 'approved')->count();
        } catch (\Exception $e) {
            $totalFundRequests = 0;
            $pendingFundRequests = 0;
            $approvedFundRequests = 0;
        }

        $stats = [
            'total_cars' => car::count(),
            'total_users' => User::count(),
            'total_fund_requests' => $totalFundRequests,
            'pending_fund_requests' => $pendingFundRequests,
            'approved_fund_requests' => $approvedFundRequests,
            'recent_cars' => car::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_users' => User::orderBy('created_at', 'desc')->limit(5)->get(),
            'cars_for_sale' => car::where('tipe', 'buy')->count(),
            'cars_for_rent' => car::where('tipe', 'rent')->count(),
            'recent_testimonials' => Testimonial::where('is_active', true)->orderBy('created_at', 'desc')->limit(5)->get(),
            'total_testimonials' => Testimonial::count(),
            'active_testimonials' => Testimonial::where('is_active', true)->count(),
            // Car approval stats
            'pending_car_approvals' => car::where('status', 'pending')->count(),
            'approved_cars' => car::where('status', 'approved')->count(),
            'rejected_cars' => car::where('status', 'rejected')->count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    private function sellerDashboard()
    {
        $user = Auth::user();

        $stats = [
            'my_total_cars' => car::where('seller_id', $user->id)->count(),
            'pending_cars' => car::where('seller_id', $user->id)->where('status', 'pending')->count(),
            'approved_cars' => car::where('seller_id', $user->id)->where('status', 'approved')->count(),
            'rejected_cars' => car::where('seller_id', $user->id)->where('status', 'rejected')->count(),
            'recent_cars' => car::where('seller_id', $user->id)->orderBy('created_at', 'desc')->limit(5)->get(),
            'cars_for_sale' => car::where('seller_id', $user->id)->where('tipe', 'buy')->where('status', 'approved')->count(),
            'cars_for_rent' => car::where('seller_id', $user->id)->where('tipe', 'rent')->where('status', 'approved')->count(),
        ];

        // Get unread notifications
        $unreadNotifications = \App\Models\NotificationLog::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadNotificationCount = \App\Models\NotificationLog::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('dashboard.seller', compact('stats', 'unreadNotifications', 'unreadNotificationCount'));
    }

    private function buyerDashboard()
    {
        $user = Auth::user();

        // Get wishlist cars for this buyer
        $wishlistCarIds = Wishlist::where('user_id', $user->id)->pluck('car_id');
        $wishlistCars = car::whereIn('id', $wishlistCarIds)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'available_cars' => car::where('status', 'approved')->count(),
            'cars_for_sale' => car::where('tipe', 'buy')->where('status', 'approved')->count(),
            'cars_for_rent' => car::where('tipe', 'rent')->where('status', 'approved')->count(),
            'recent_cars' => car::where('status', 'approved')->orderBy('created_at', 'desc')->limit(5)->get(),
            'wishlist_count' => $wishlistCarIds->count(),
            'wishlist_cars' => $wishlistCars,
        ];

        return view('dashboard.buyer', compact('stats'));
    }
}

