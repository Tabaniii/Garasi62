<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\car;
use App\Models\CarApproval;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarApprovalController extends Controller
{
    /**
     * Display a listing of pending car approvals
     */
    public function index()
    {
        $pendingCars = car::with([
                'seller',
                'approvals' => function($q) {
                    $q->orderBy('approved_at', 'desc');
                }
            ])
            ->where('status', 'pending')
            ->whereDoesntHave('approvals', function($q) {
                $q->where('action', 'rejected');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            // Hanya pending tanpa riwayat 'rejected' (bukan pengajuan ulang)
            'pending' => car::where('status', 'pending')
                ->whereDoesntHave('approvals', function($q) {
                    $q->where('action', 'rejected');
                })->count(),
            'approved' => car::where('status', 'approved')->count(),
            'rejected' => car::where('status', 'rejected')->count(),
        ];

        return view('admin.car-approvals.index', compact('pendingCars', 'stats'));
    }
    
    /**
     * Display cars that were previously rejected and have been resubmitted (now pending)
     */
    public function resubmissionsIndex()
    {
        $resubmittedCars = car::with([
                'seller',
                'approvals' => function($q) {
                    $q->where('action', 'rejected')->orderBy('approved_at', 'desc');
                }
            ])
            ->where('status', 'pending')
            ->whereHas('approvals', function($q) {
                $q->where('action', 'rejected');
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('admin.car-approvals.resubmissions', compact('resubmittedCars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified car for approval
     */
    public function show(car $car)
    {
        if ($car->status !== 'pending') {
            return redirect()->route('admin.car-approvals.index')
                ->with('error', 'Mobil ini sudah diproses.');
        }

        $car->load('seller', 'approvals.admin');

        return view('admin.car-approvals.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Approve a car
     */
    public function approve(Request $request, car $car)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($car->status !== 'pending') {
            return redirect()->route('admin.car-approvals.index')
                ->with('error', 'Mobil ini sudah diproses.');
        }

        DB::transaction(function () use ($car, $request) {
            // Update car status
            $car->update(['status' => 'approved']);

            // Create approval record
            CarApproval::create([
                'car_id' => $car->id,
                'admin_id' => Auth::id(),
                'action' => 'approved',
                'notes' => $request->notes,
                'approved_at' => now(),
            ]);

            // Create notification message for seller
            $notificationMessage = "Mobil {$car->brand} {$car->nama} yang Anda posting telah disetujui.";
            if ($request->notes) {
                $notificationMessage .= " Catatan: " . $request->notes;
            }

            // Create notification record in database
            NotificationLog::create([
                'user_id' => $car->seller_id,
                'title' => 'Mobil Disetujui',
                'message' => $notificationMessage,
                'is_read' => false,
            ]);
        });

        return redirect()->route('admin.car-approvals.index')
            ->with('success', 'Mobil berhasil disetujui.');
    }

    /**
     * Reject a car
     */
    public function reject(Request $request, car $car)
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        if ($car->status !== 'pending') {
            return redirect()->route('admin.car-approvals.index')
                ->with('error', 'Mobil ini sudah diproses.');
        }

        DB::transaction(function () use ($car, $request) {
            // Update car status
            $car->update(['status' => 'rejected']);

            // Create approval record
            CarApproval::create([
                'car_id' => $car->id,
                'admin_id' => Auth::id(),
                'action' => 'rejected',
                'notes' => $request->notes,
                'approved_at' => now(),
            ]);

            // Create notification message for seller
            $notificationMessage = "Mobil {$car->brand} {$car->nama} yang Anda posting telah diproses.";
            if ($request->notes) {
                $notificationMessage .= " Catatan: " . $request->notes;
            }

            // Here you can add more advanced notification logic
            // For example: send email to seller, create notification record in database, push notification, etc.
        });

        return redirect()->route('admin.car-approvals.index')
            ->with('success', 'Mobil berhasil ditolak.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show approval history
     */
    public function history()
    {
        $approvals = CarApproval::with(['car.seller', 'admin'])
            ->orderBy('approved_at', 'desc')
            ->paginate(20);

        return view('admin.car-approvals.history', compact('approvals'));
    }
}
