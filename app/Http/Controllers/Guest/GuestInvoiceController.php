<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class GuestInvoiceController extends Controller
{
    /**
     * Display guest's invoices
     */
    public function index()
    {
        $guest = auth()->user()->guest;
        $invoices = Invoice::where('guest_id', $guest->id)
            ->latest()
            ->paginate(15);

        return view('guest.invoices.index', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show invoice details
     */
    public function show(Invoice $invoice)
    {
        // Ensure guest can only view own invoices
        if ($invoice->guest_id !== auth()->user()->guest->id) {
            abort(403);
        }

        $invoice->load('reservation', 'payments');
        return view('guest.invoices.show', ['invoice' => $invoice]);
    }

    /**
     * Download invoice as PDF
     */
    public function download(Invoice $invoice)
    {
        // Ensure guest can only download own invoices
        if ($invoice->guest_id !== auth()->user()->guest->id) {
            abort(403);
        }

        // TODO: Implement PDF generation
        return back()->with('info', 'PDF download coming soon.');
    }
}
