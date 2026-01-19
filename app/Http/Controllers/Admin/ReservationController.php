<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Guest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations
     */
    public function index()
    {
        $reservations = Reservation::with('guest', 'room')
            ->latest()
            ->paginate(15);

        return view('admin.reservations.index', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Show the form for creating a new reservation
     */
    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        $guests = Guest::all();
        return view('admin.reservations.create', [
            'rooms' => $rooms,
            'guests' => $guests,
        ]);
    }

    /**
     * Store a newly created reservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string',
        ]);

        $reservation = Reservation::create($validated + [
            'status' => 'confirmed',
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified reservation
     */
    public function show(Reservation $reservation)
    {
        $reservation->load('guest', 'room', 'invoices');
        return view('admin.reservations.show', ['reservation' => $reservation]);
    }

    /**
     * Show the form for editing the reservation
     */
    public function edit(Reservation $reservation)
    {
        $rooms = Room::get();
        return view('admin.reservations.edit', [
            'reservation' => $reservation,
            'rooms' => $rooms,
        ]);
    }

    /**
     * Update the specified reservation
     */
    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reservation updated successfully.');
    }

    /**
     * Check-in a guest
     */
    public function checkIn(Reservation $reservation)
    {
        $reservation->update(['status' => 'checked_in']);
        return back()->with('success', 'Guest checked in successfully.');
    }

    /**
     * Check-out a guest
     */
    public function checkOut(Reservation $reservation)
    {
        $reservation->update(['status' => 'checked_out']);
        return back()->with('success', 'Guest checked out successfully.');
    }

    /**
     * Delete the specified reservation
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')
            ->with('success', 'Reservation deleted successfully.');
    }
}
