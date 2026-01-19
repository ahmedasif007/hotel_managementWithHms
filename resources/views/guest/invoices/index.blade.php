@extends('layouts.app')

@section('title', 'My Invoices')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Invoices</h1>
        <p class="text-gray-600 mt-2">View and download your invoices</p>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Invoice #</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Room</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Amount</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($invoices ?? [] as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $invoice->invoice_number ?? '#INV-000' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->created_at?->format('M d, Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($invoice->reservation)
                                    Room {{ $invoice->reservation->room->room_number ?? '#' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">${{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($invoice->status === 'paid') bg-green-100 text-green-800
                                    @elseif($invoice->status === 'sent') bg-blue-100 text-blue-800
                                    @elseif($invoice->status === 'overdue') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($invoice->status ?? 'draft') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-3">
                                <a href="/guest/invoices/{{ $invoice->id }}" class="text-blue-600 hover:text-blue-700">View</a>
                                <a href="/guest/invoices/{{ $invoice->id }}/download" class="text-blue-600 hover:text-blue-700">Download</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No invoices yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
