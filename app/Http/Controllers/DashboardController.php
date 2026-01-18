<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardStatisticResource;
use App\Models\Guest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function statistics(): JsonResponse
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $totalGuests = Guest::count();
        $currentReservations = Reservation::where('status', 'checked_in')->count();
        $totalRevenue = Invoice::sum('total');
        $pendingPayments = Invoice::where('status', 'pending')->count();

        $statistics = [
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'occupied_rooms' => $occupiedRooms,
            'total_guests' => $totalGuests,
            'current_reservations' => $currentReservations,
            'total_revenue' => $totalRevenue,
            'pending_payments' => $pendingPayments,
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    public function recentReservations(): JsonResponse
    {
        $reservations = Reservation::with(['guest', 'room'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    }

    public function recentPayments(): JsonResponse
    {
        $payments = Payment::with(['invoice'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    public function revenue(): JsonResponse
    {
        $revenue = Invoice::selectRaw('DATE(issue_date) as date, SUM(total) as total')
            ->whereYear('issue_date', now()->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $revenue,
        ]);
    }
}
