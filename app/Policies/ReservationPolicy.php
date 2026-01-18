<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reservation;

class ReservationPolicy
{
    public function view(User $user, Reservation $reservation)
    {
        return $user->hasPermission('view_reservations') || $user->id === $reservation->guest_id;
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_reservation');
    }

    public function update(User $user, Reservation $reservation)
    {
        return $user->hasPermission('edit_reservation');
    }

    public function delete(User $user, Reservation $reservation)
    {
        return $user->hasPermission('cancel_reservation');
    }

    public function checkIn(User $user)
    {
        return $user->hasPermission('check_in');
    }

    public function checkOut(User $user)
    {
        return $user->hasPermission('check_out');
    }
}
