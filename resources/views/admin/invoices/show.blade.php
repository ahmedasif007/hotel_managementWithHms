@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="/invoices" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">← Back to Invoices</a>
        <h1 class="text-3xl font-bold text-gray-900">Invoice {{ $invoice->invoice_number ?? '#INV-000' }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Invoice Card -->
            <div class="bg-white rounded-lg shadow p-8 mb-8">
                <!-- Invoice Header -->
                <div class="grid grid-cols-2 mb-8 pb-8 border-b border-gray-200">
                    <div>
                        <p class="text-gray-600 text-sm">Invoice Number</p>
                        <p class="font-bold text-gray-900 text-lg">{{ $invoice->invoice_number ?? '#INV-000' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600 text-sm">Invoice Date</p>
                        <p class="font-bold text-gray-900 text-lg">{{ $invoice->created_at?->format('M d, Y') ?? '-' }}</p>
                    </div>
                </div>

                <!-- Guest & Amount Info -->
                <div class="grid grid-cols-2 mb-8">
                    <div>
                        <p class="text-gray-600 text-sm mb-2">Bill To</p>
                        <div class="font-semibold text-gray-900">
                            <p>{{ $invoice->guest->name ?? 'Guest' }}</p>
                            <p class="text-sm text-gray-600">{{ $invoice->guest->email ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $invoice->guest->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600 text-sm mb-2">Status</p>
                        <span class="px-4 py-2 text-sm font-medium rounded-full
                            @if($invoice->status === 'paid') bg-green-100 text-green-800
                            @elseif($invoice->status === 'sent') bg-blue-100 text-blue-800
                            @elseif($invoice->status === 'overdue') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($invoice->status ?? 'draft') }}
                        </span>
                    </div>
                </div>

                <!-- Line Items -->
                <div class="mb-8">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-t border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-900">Description</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-900">Qty</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-900">Unit Price</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-900">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-gray-900">
                                    Room {{ $invoice->reservation->room->room_number ?? '#' }}
                                    <br>
                                    <span class="text-sm text-gray-600">{{ $invoice->reservation->check_in_date?->format('M d') ?? '-' }} - {{ $invoice->reservation->check_out_date?->format('M d, Y') ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-900">{{ $invoice->reservation->number_of_nights ?? 0 }}</td>
                                <td class="px-4 py-3 text-right text-gray-900">${{ number_format($invoice->reservation->room->price_per_night ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">${{ number_format(($invoice->reservation->room->price_per_night ?? 0) * ($invoice->reservation->number_of_nights ?? 0), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="grid grid-cols-2 gap-8">
                    <div></div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900 font-medium">${{ number_format(($invoice->total_amount ?? 0) * 0.9, 2) }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Tax (10%)</span>
                            <span class="text-gray-900 font-medium">${{ number_format(($invoice->total_amount ?? 0) * 0.1, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="font-bold text-gray-900 text-lg">${{ number_format($invoice->total_amount ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment History</h2>
                
                @forelse($invoice->payments ?? [] as $payment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-2">
                        <div>
                            <p class="font-medium text-gray-900">{{ ucfirst($payment->payment_method ?? 'unknown') }}</p>
                            <p class="text-sm text-gray-600">{{ $payment->created_at?->format('M d, Y H:i') ?? '-' }}</p>
                        </div>
                        <span class="font-semibold text-green-600">${{ number_format($payment->amount ?? 0, 2) }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No payments recorded</p>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6 sticky top-20">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-2">
                    <a href="/invoices/{{ $invoice->id }}/download" class="w-full block bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700 transition">
                        Download PDF
                    </a>
                    
                    @if($invoice->status !== 'paid')
                        <a href="/invoices/{{ $invoice->id }}/pay" class="w-full block bg-green-600 text-white px-4 py-2 rounded-lg text-center hover:bg-green-700 transition">
                            Make Payment
                        </a>
                    @endif

                    @if($invoice->status === 'draft')
                        <a href="/invoices/{{ $invoice->id }}/send" class="w-full block bg-gray-600 text-white px-4 py-2 rounded-lg text-center hover:bg-gray-700 transition">
                            Send Invoice
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
