<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMonthlyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $reservations = Reservation::whereMonth('check_in_date', now()->month)
            ->whereYear('check_in_date', now()->year)
            ->get();

        $totalRooms = count($reservations);
        $totalRevenue = $reservations->sum(function($reservation) {
            return $reservation->invoice?->total ?? 0;
        });

        \Log::info('Monthly Report Generated', [
            'month' => now()->format('F Y'),
            'total_reservations' => $totalRooms,
            'total_revenue' => $totalRevenue,
        ]);
    }
}
