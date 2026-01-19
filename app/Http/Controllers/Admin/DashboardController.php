<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'active_reservations' => Reservation::whereIn('status', ['confirmed', 'checked_in'])->count(),
            'monthly_revenue' => Invoice::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'paid')
                ->sum('total_amount'),
        ];

        $recentReservations = Reservation::with('guest', 'room')
            ->latest()
            ->limit(5)
            ->get();

        $pendingInvoices = Invoice::with('guest')
            ->where('status', '!=', 'paid')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentReservations' => $recentReservations,
            'pendingInvoices' => $pendingInvoices,
        ]);
    }
}
