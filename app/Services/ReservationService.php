<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationService
{
    public function createReservation(array $data): Reservation
    {
        // Check room availability
        $room = Room::findOrFail($data['room_id']);
        $checkInDate = Carbon::parse($data['check_in_date']);
        $checkOutDate = Carbon::parse($data['check_out_date']);

        if (!$room->isAvailable($checkInDate, $checkOutDate)) {
            throw new \Exception('Room is not available for the selected dates.');
        }

        // Calculate total amount
        $numberOfNights = $checkOutDate->diffInDays($checkInDate);
        $totalAmount = $room->price_per_night * $numberOfNights;

        $data['total_amount'] = $totalAmount;
        $data['status'] = 'confirmed';

        return Reservation::create($data);
    }

    public function checkIn(Reservation $reservation): void
    {
        if ($reservation->status !== 'confirmed') {
            throw new \Exception('Only confirmed reservations can be checked in.');
        }

        $reservation->update([
            'status' => 'checked_in',
        ]);

        $reservation->room->update(['status' => 'occupied']);
    }

    public function checkOut(Reservation $reservation): void
    {
        if ($reservation->status !== 'checked_in') {
            throw new \Exception('Reservation must be checked in to check out.');
        }

        $reservation->update(['status' => 'checked_out']);
        $reservation->room->update(['status' => 'available']);
    }
}
