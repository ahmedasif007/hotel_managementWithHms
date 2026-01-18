<?php

namespace App\Listeners;

use App\Events\ReservationConfirmed;
use App\Mail\ReservationConfirmed as ReservationConfirmedMail;
use Illuminate\Support\Facades\Mail;

class SendReservationConfirmationEmail
{
    public function handle(ReservationConfirmed $event)
    {
        Mail::to($event->reservation->guest->email)
            ->send(new ReservationConfirmedMail($event->reservation));
    }
}
