<?php

namespace App\Observers;

use App\Events\ReservationConfirmed;
use App\Models\Reservation;

class ReservationObserver
{
    public function updated(Reservation $reservation)
    {
        // Dispatch event when reservation status changes to confirmed
        if ($reservation->isDirty('status') && $reservation->status === 'confirmed') {
            ReservationConfirmed::dispatch($reservation);
        }
    }

    public function created(Reservation $reservation)
    {
        // You can perform other actions on creation
    }

    public function deleted(Reservation $reservation)
    {
        // You can perform cleanup actions on deletion
    }
}
