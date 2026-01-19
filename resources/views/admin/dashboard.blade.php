@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Key Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Rooms -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Rooms</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_rooms'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.5 1.5H5.25A1.25 1.25 0 004 2.75v14.5A1.25 1.25 0 005.25 18h9.5a1.25 1.25 0 001.25-1.25V6.5"></path>
                    </svg>
                </div>
            </div>
            <a href="/rooms" class="text-blue-600 text-sm mt-4 inline-block hover:text-blue-700">View rooms →</a>
        </div>

        <!-- Available Rooms -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Available Rooms</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['available_rooms'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <p class="text-gray-500 text-sm mt-4">{{ number_format(($stats['available_rooms'] ?? 0) / max($stats['total_rooms'] ?? 1, 1) * 100) }}% occupancy</p>
        </div>

        <!-- Active Reservations -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Reservations</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['active_reservations'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                    </svg>
                </div>
            </div>
            <a href="/reservations" class="text-blue-600 text-sm mt-4 inline-block hover:text-blue-700">View reservations →</a>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Monthly Revenue</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">${{ number_format($stats['monthly_revenue'] ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.16 2.75a.75.75 0 00-1.32 0l-1.917 5.06H.75a.75.75 0 00-.718 1.03l.03.08 4.25 6.17a.75.75 0 001.222 0l4.25-6.17a.75.75 0 00.03-.08.75.75 0 00-.718-1.03h-4.163L8.16 2.75zM16.75 10a.75.75 0 00-1.32 0l-1.917 5.06h-4.163a.75.75 0 00-.718 1.03l.03.08 4.25 6.17a.75.75 0 001.222 0l4.25-6.17a.75.75 0 00.03-.08.75.75 0 00-.718-1.03h-4.163L16.75 10z"></path>
                    </svg>
                </div>
            </div>
            <a href="/invoices" class="text-blue-600 text-sm mt-4 inline-block hover:text-blue-700">View invoices →</a>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Reservations -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Reservations</h2>
                <a href="/reservations" class="text-blue-600 text-sm hover:text-blue-700">View all →</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentReservations ?? [] as $reservation)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-900">{{ $reservation->guest->name ?? 'Guest' }}</p>
                            <p class="text-sm text-gray-500">Room {{ $reservation->room->room_number ?? 'N/A' }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($reservation->status ?? 'pending') }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p>No recent reservations</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Invoices -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Pending Invoices</h2>
                <a href="/invoices" class="text-blue-600 text-sm hover:text-blue-700">View all →</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($pendingInvoices ?? [] as $invoice)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-900">{{ $invoice->invoice_number ?? '#INV-000' }}</p>
                            <p class="text-sm text-gray-500">{{ $invoice->guest->name ?? 'Guest' }}</p>
                        </div>
                        <span class="font-medium text-red-600">${{ number_format($invoice->total_amount ?? 0, 2) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p>No pending invoices</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Occupancy Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Occupancy Rate</h2>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Chart will display occupancy data</p>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h2>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Chart will display revenue data</p>
            </div>
        </div>
    </div>
</div>
@endsection
