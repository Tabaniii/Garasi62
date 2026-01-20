<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\car;
use App\Models\NotificationLog;
use App\Mail\CarUnpublishedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
            // Admin can see all reports with actions
            return view('admin.reports.show', compact('report'));
        } elseif ($user->role === 'seller') {
            // Seller can only see reports for their cars (read-only)
            if ($report->seller_id !== $user->id) {
                return redirect()->route('seller.reports.index')
                    ->with('error', 'Anda tidak memiliki akses ke laporan ini.');
            }
            // Return seller-specific view (read-only)
            return view('seller.reports.show', compact('report'));
        } else {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    /**
     * Update report status (Admin only)
     */
    public function update(Request $request, Report $report)
    {
        // Ensure only admin can access this
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

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

    /**
     * Unpublish car (Admin only)
     */
    public function unpublishCar(Request $request, Report $report)
    {
        // Ensure only admin can access this
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $car = $report->car;

        if (!$car) {
            return redirect()->route('admin.reports.show', $report)
                ->with('error', 'Mobil tidak ditemukan.');
        }

        $adminNotes = $request->admin_notes ?? 'Mobil telah di-unpublish karena laporan yang diterima.';

        DB::transaction(function () use ($car, $report, $adminNotes) {
            // Unpublish car (change status to rejected or unpublished)
            $car->update([
                'status' => 'rejected',
            ]);

            // Update report status
            $report->update([
                'status' => 'resolved',
                'admin_notes' => $adminNotes,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // Notify seller jika seller ada
            if ($car->seller_id) {
                $seller = \App\Models\Users::find($car->seller_id);
                
                if ($seller) {
                    $carName = "{$car->brand} {$car->nama}";
                    $reportUrl = route('seller.reports.show', $report);
                    
                    // Load reporter untuk notifikasi
                    $report->load('reporter');
                    
                    // Buat notifikasi di database dengan detail laporan
                    $notificationMessage = "Mobil Anda ({$carName}) telah di-unpublish oleh admin karena laporan yang diterima.\n\n";
                    $notificationMessage .= "Alasan Laporan: {$report->reason_label}\n";
                    if ($report->reporter) {
                        $notificationMessage .= "Dilaporkan oleh: {$report->reporter->name}\n";
                    }
                    $notificationMessage .= "Tanggal Laporan: {$report->created_at->format('d M Y, H:i')}\n";
                    $notificationMessage .= "Detail: " . Str::limit($report->message, 100) . "\n\n";
                    $notificationMessage .= "Keterangan Admin: {$adminNotes}\n\n";
                    $notificationMessage .= "Lihat detail lengkap laporan di: {$reportUrl}";
                    
                    NotificationLog::create([
                        'user_id' => $seller->id,
                        'title' => 'Mobil Di-Unpublish',
                        'message' => $notificationMessage,
                        'is_read' => false,
                    ]);

                    // Kirim email notifikasi ke seller dengan detail lengkap
                    try {
                        Mail::to($seller->email)->send(new CarUnpublishedMail(
                            $seller->name,
                            $carName,
                            $car->brand,
                            $car->nama,
                            $adminNotes,
                            $report->reason_label,
                            $report->message,
                            $report->reporter ? $report->reporter->name : 'Pengguna',
                            $report->created_at->format('d M Y, H:i'),
                            $reportUrl,
                            $report->id
                        ));
                    } catch (\Exception $e) {
                        // Log error tapi jangan gagalkan proses
                        \Log::error('Failed to send unpublish notification email: ' . $e->getMessage());
                    }
                }
            }
        });

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Mobil berhasil di-unpublish dan seller telah diberitahu.');
    }
}
