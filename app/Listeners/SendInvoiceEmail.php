<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use App\Mail\InvoiceSent;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmail
{
    public function handle(InvoiceCreated $event)
    {
        Mail::to($event->invoice->reservation->guest->email)
            ->send(new InvoiceSent($event->invoice));
    }
}
