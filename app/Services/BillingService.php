<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Reservation;

class BillingService
{
    public function generateInvoice(Reservation $reservation): Invoice
    {
        $numberOfNights = $reservation->check_out_date->diffInDays($reservation->check_in_date);
        $subtotal = $reservation->room->price_per_night * $numberOfNights;
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        return Invoice::updateOrCreate(
            ['reservation_id' => $reservation->id],
            [
                'invoice_number' => 'INV-' . $reservation->id . '-' . now()->timestamp,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'issued_at' => now(),
                'status' => 'issued',
            ]
        );
    }

    public function getInvoiceDetails(Invoice $invoice): array
    {
        return [
            'invoice' => $invoice,
            'reservation' => $invoice->reservation,
            'guest' => $invoice->reservation->guest,
            'room' => $invoice->reservation->room,
            'payments' => $invoice->payments,
            'due_amount' => $invoice->total - $invoice->payments->sum('amount'),
        ];
    }
}
