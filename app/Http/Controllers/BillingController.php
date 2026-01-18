<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function createInvoice($reservationId)
    {
        $invoice = Invoice::query()
            ->where('reservation_id', $reservationId)
            ->firstOrCreate(
                ['reservation_id' => $reservationId],
                [
                    'invoice_number' => 'INV-' . now()->format('YmdHis'),
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                    'status' => 'draft',
                ]
            );

        return response()->json($invoice);
    }

    public function recordPayment(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,online,bank_transfer',
            'reference_number' => 'nullable|string',
        ]);

        $payment = Payment::create([
            ...$validated,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return response()->json($payment, 201);
    }

    public function getInvoice($id)
    {
        $invoice = Invoice::with('reservation.guest', 'reservation.room', 'payments')
            ->findOrFail($id);
        return response()->json($invoice);
    }

    public function listInvoices()
    {
        $invoices = Invoice::with('reservation.guest')
            ->orderBy('issued_at', 'desc')
            ->get();
        return response()->json($invoices);
    }
}
