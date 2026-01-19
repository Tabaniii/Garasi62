<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Store a new report
     */
    public function store(Request $request, $carId)
    {
        $request->validate([
            'reason' => 'required|in:false_information,inappropriate_content,spam,duplicate,scam,other',
            'message' => 'required|string|min:10|max:1000',
        ]);

        $car = car::findOrFail($carId);

        // Check if user is trying to report their own car
        if ($car->seller_id && $car->seller_id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat melaporkan mobil Anda sendiri.');
        }

        // Check if user already reported this car
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('car_id', $carId)
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            return back()->with('error', 'Anda sudah melaporkan mobil ini sebelumnya. Silakan tunggu review dari admin.');
        }

        // Create report
        Report::create([
            'car_id' => $carId,
            'reporter_id' => Auth::id(),
            'seller_id' => $car->seller_id,
            'reason' => $request->reason,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('reports.my-reports')
            ->with('success', 'Laporan berhasil dikirim! Admin akan meninjau laporan Anda dalam 1-3 hari kerja. Anda dapat melihat status laporan di halaman "Laporan Saya".');
    }

    /**
     * Display reports for current user (reporter)
     */
    public function myReports()
    {
        $user = Auth::user();

        $reports = Report::with(['car', 'seller', 'reviewer'])
            ->where('reporter_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'pending' => Report::where('reporter_id', $user->id)->where('status', 'pending')->count(),
            'reviewed' => Report::where('reporter_id', $user->id)->where('status', 'reviewed')->count(),
            'resolved' => Report::where('reporter_id', $user->id)->where('status', 'resolved')->count(),
            'dismissed' => Report::where('reporter_id', $user->id)->where('status', 'dismissed')->count(),
            'total' => Report::where('reporter_id', $user->id)->count(),
        ];

        return view('reports.my-reports', compact('reports', 'stats'));
    }

    /**
     * Display all reports (Admin)
     */
    public function index(Request $request)
    {
        $query = Report::with(['car', 'reporter', 'seller', 'reviewer']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'pending' => Report::where('status', 'pending')->count(),
            'reviewed' => Report::where('status', 'reviewed')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'dismissed' => Report::where('status', 'dismissed')->count(),
        ];

        return view('admin.reports.index', compact('reports', 'stats'));
    }

    /**
     * Display reports for seller
     */
    public function sellerIndex()
    {
        $user = Auth::user();

        $reports = Report::with(['car', 'reporter', 'reviewer'])
            ->where('seller_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'pending' => Report::where('seller_id', $user->id)->where('status', 'pending')->count(),
            'total' => Report::where('seller_id', $user->id)->count(),
        ];

        return view('seller.reports.index', compact('reports', 'stats'));
    }

    /**
     * Show report details
     */
    public function show(Report $report)
    {
        $report->load(['car', 'reporter', 'seller', 'reviewer']);

        // Check permissions
        $user = Auth::user();
        if ($user->role === 'admin') {
            // Admin can see all reports
        } elseif ($user->role === 'seller') {
            // Seller can only see reports for their cars
            if ($report->seller_id !== $user->id) {
                return redirect()->route('seller.reports.index')
                    ->with('error', 'Anda tidak memiliki akses ke laporan ini.');
            }
        } else {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Update report status (Admin only)
     */
    public function update(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.reports.index')
            ->with('success', 'Status laporan berhasil diperbarui.');
    }
}
