<?php

namespace App\Console\Commands;

use App\Jobs\SendCheckInReminder;
use App\Models\Reservation;
use Illuminate\Console\Command;

class SendCheckInReminders extends Command
{
    protected $signature = 'reservation:send-checkin-reminders';
    protected $description = 'Send check-in reminder emails for reservations';

    public function handle()
    {
        $this->info('Sending check-in reminders...');

        // Get reservations checking in tomorrow
        $reservations = Reservation::where('check_in_date', now()->addDay()->format('Y-m-d'))
            ->where('status', 'confirmed')
            ->get();

        foreach ($reservations as $reservation) {
            SendCheckInReminder::dispatch($reservation);
            $this->line("Reminder sent for reservation {$reservation->id}");
        }

        $this->info("Sent {$reservations->count()} check-in reminders");
    }
}
