<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index()
    {
        $invoices = Invoice::with('guest')
            ->latest()
            ->paginate(20);

        $stats = [
            'total_invoices' => Invoice::count(),
            'paid_amount' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_amount' => Invoice::where('status', '!=', 'paid')->sum('total_amount'),
            'overdue_amount' => Invoice::where('status', 'overdue')->sum('total_amount'),
        ];

        return view('admin.invoices.index', [
            'invoices' => $invoices,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('guest', 'reservation', 'payments');
        return view('admin.invoices.show', ['invoice' => $invoice]);
    }

    /**
     * Download invoice as PDF
     */
    public function download(Invoice $invoice)
    {
        // TODO: Implement PDF generation
        return back()->with('info', 'PDF download coming soon.');
    }
}
