<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckInReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Check-In Reminder')
            ->view('emails.checkin-reminder')
            ->with([
                'guest' => $this->reservation->guest,
                'reservation' => $this->reservation,
            ]);
    }
}
