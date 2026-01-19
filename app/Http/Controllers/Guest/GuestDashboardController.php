<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Invoice;
use Illuminate\Http\Request;

class GuestDashboardController extends Controller
{
    /**
     * Display the guest dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $guest = $user->guest;

        $myReservations = Reservation::where('guest_id', $guest->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->with('room')
            ->latest()
            ->get();

        $pastReservations = Reservation::where('guest_id', $guest->id)
            ->whereIn('status', ['checked_out', 'cancelled'])
            ->with('room')
            ->latest()
            ->limit(5)
            ->get();

        $recentInvoices = Invoice::where('guest_id', $guest->id)
            ->latest()
            ->limit(5)
            ->get();

        $activeBookings = $myReservations->count();
        $nextCheckIn = $myReservations->first()?->check_in_date;
        $totalSpent = Invoice::where('guest_id', $guest->id)
            ->where('status', 'paid')
            ->sum('total_amount');

        return view('guest.dashboard', [
            'activeBookings' => $activeBookings,
            'nextCheckIn' => $nextCheckIn,
            'totalSpent' => $totalSpent,
            'myReservations' => $myReservations,
            'pastReservations' => $pastReservations,
            'recentInvoices' => $recentInvoices,
        ]);
    }
}
