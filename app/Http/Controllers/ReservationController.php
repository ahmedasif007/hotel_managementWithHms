<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(private ReservationService $service)
    {
    }

    public function index()
    {
        $reservations = Reservation::with('guest', 'room', 'invoice')
            ->orderBy('check_in_date', 'desc')
            ->get();
        return response()->json($reservations);
    }

    public function show($id)
    {
        $reservation = Reservation::with('guest', 'room', 'invoice', 'payments')
            ->findOrFail($id);
        return response()->json($reservation);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $reservation = $this->service->createReservation($validated);
        return response()->json($reservation, 201);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'check_in_date' => 'date|after_or_equal:today',
            'check_out_date' => 'date|after:check_in_date',
            'number_of_guests' => 'integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $reservation->update($validated);
        return response()->json($reservation);
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Reservation cancelled']);
    }

    public function checkIn($id)
    {
        $reservation = Reservation::findOrFail($id);
        $this->service->checkIn($reservation);
        return response()->json(['message' => 'Guest checked in']);
    }

    public function checkOut($id)
    {
        $reservation = Reservation::findOrFail($id);
        $this->service->checkOut($reservation);
        return response()->json(['message' => 'Guest checked out']);
    }
}
