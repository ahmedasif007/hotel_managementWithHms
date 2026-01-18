<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceSent extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        return $this->subject('Invoice #' . $this->invoice->invoice_number)
            ->view('emails.invoice-sent')
            ->with([
                'invoice' => $this->invoice,
                'guest' => $this->invoice->reservation->guest,
            ]);
    }
}
