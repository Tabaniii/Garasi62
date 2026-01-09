<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use App\Models\User;
use App\Models\FundRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
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
            'cars_for_sale' => car::where('tipe', 'sale')->count(),
            'cars_for_rent' => car::where('tipe', 'rent')->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}

