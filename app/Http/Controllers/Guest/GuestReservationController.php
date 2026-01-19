<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;

class GuestReservationController extends Controller
{
    /**
     * Display guest's reservations
     */
    public function index()
    {
        $guest = auth()->user()->guest;
        $reservations = Reservation::where('guest_id', $guest->id)
            ->with('room')
            ->latest()
            ->paginate(10);

        return view('guest.reservations.index', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Show create reservation form
     */
    public function create(Request $request)
    {
        $roomId = $request->query('room_id');
        $room = $roomId ? Room::find($roomId) : null;

        return view('guest.reservations.create', [
            'room' => $room,
        ]);
    }

    /**
     * Store a new reservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string',
        ]);

        $guest = auth()->user()->guest;

        $reservation = Reservation::create([
            'guest_id' => $guest->id,
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('guest.reservations.show', $reservation)
            ->with('success', 'Reservation created successfully. Awaiting confirmation.');
    }

    /**
     * Show reservation details
     */
    public function show(Reservation $reservation)
    {
        // Ensure guest can only view own reservations
        if ($reservation->guest_id !== auth()->user()->guest->id) {
            abort(403);
        }

        $reservation->load('room', 'invoices');
        return view('guest.reservations.show', ['reservation' => $reservation]);
    }

    /**
     * Cancel a reservation
     */
    public function cancel(Reservation $reservation)
    {
        // Ensure guest can only cancel own reservations
        if ($reservation->guest_id !== auth()->user()->guest->id) {
            abort(403);
        }

        if ($reservation->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed reservations can be cancelled.');
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('guest.reservations.index')
            ->with('success', 'Reservation cancelled successfully.');
    }
}
