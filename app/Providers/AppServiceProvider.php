<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Reservation;
use App\Observers\InvoiceObserver;
use App\Observers\ReservationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register observers
        Reservation::observe(ReservationObserver::class);
        Invoice::observe(InvoiceObserver::class);
    }
}
