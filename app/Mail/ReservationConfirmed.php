<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Reservation Confirmed')
            ->view('emails.reservation-confirmed')
            ->with([
                'guest' => $this->reservation->guest,
                'room' => $this->reservation->room,
                'reservation' => $this->reservation,
            ]);
    }
}
