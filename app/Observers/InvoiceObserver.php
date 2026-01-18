<?php

namespace App\Observers;

use App\Events\InvoiceCreated;
use App\Models\Invoice;

class InvoiceObserver
{
    public function created(Invoice $invoice)
    {
        // Dispatch event when invoice is created
        InvoiceCreated::dispatch($invoice);
    }
}
