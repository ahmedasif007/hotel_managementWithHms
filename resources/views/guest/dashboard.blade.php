@extends('layouts.app')

@section('title', 'Guest Dashboard')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mt-2">Manage your bookings and account</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Active Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Bookings</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeBookings ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Upcoming Check-ins -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Next Check-in</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $nextCheckIn?->format('M d') ?? 'None' }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 11a3 3 0 110-6 3 3 0 010 6zM9 3a6 6 0 100 12A6 6 0 009 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Spent</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($totalSpent ?? 0, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.16 2.75a.75.75 0 00-1.32 0l-1.917 5.06H.75a.75.75 0 00-.718 1.03l.03.08 4.25 6.17a.75.75 0 001.222 0l4.25-6.17a.75.75 0 00.03-.08.75.75 0 00-.718-1.03h-4.163L8.16 2.75z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Active Reservations -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">My Reservations</h2>
                </div>
                
                @forelse($myReservations ?? [] as $reservation)
                    <div class="p-6 border-b border-gray-200 hover:bg-gray-50 transition last:border-b-0">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-900">Room {{ $reservation->room->room_number ?? '#' }}</h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($reservation->status ?? 'pending') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4 text-sm mb-3">
                            <div>
                                <p class="text-gray-500">Check-in</p>
                                <p class="font-medium text-gray-900">{{ $reservation->check_in_date?->format('M d, Y') ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Check-out</p>
                                <p class="font-medium text-gray-900">{{ $reservation->check_out_date?->format('M d, Y') ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Total Price</p>
                                <p class="font-medium text-gray-900">${{ number_format($reservation->total_price ?? 0, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="/guest/reservations/{{ $reservation->id }}" class="text-blue-600 text-sm hover:text-blue-700">View Details</a>
                            @if($reservation->status === 'confirmed')
                                <form method="POST" action="/guest/reservations/{{ $reservation->id }}/cancel" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="text-red-600 text-sm hover:text-red-700" onclick="return confirm('Are you sure?')">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <p>No reservations yet</p>
                        <a href="/rooms" class="text-blue-600 mt-2 inline-block hover:text-blue-700">Browse rooms →</a>
                    </div>
                @endforelse
            </div>

            <!-- Booking History -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Past Bookings</h2>
                </div>
                
                @forelse($pastReservations ?? [] as $reservation)
                    <div class="px-6 py-4 border-b border-gray-200 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">Room {{ $reservation->room->room_number ?? '#' }}</p>
                                <p class="text-sm text-gray-500">{{ $reservation->check_in_date?->format('M d') ?? '-' }} - {{ $reservation->check_out_date?->format('M d, Y') ?? '-' }}</p>
                            </div>
                            <a href="/guest/reservations/{{ $reservation->id }}" class="text-blue-600 text-sm hover:text-blue-700">View</a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p>No past bookings</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <a href="/rooms" class="w-full block bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700 transition mb-2">
                    Browse Rooms
                </a>
                <a href="/guest/reservations/create" class="w-full block bg-gray-100 text-gray-900 px-4 py-2 rounded-lg text-center hover:bg-gray-200 transition">
                    New Booking
                </a>
            </div>

            <!-- Recent Invoices -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Invoices</h3>
                
                <div class="space-y-3">
                    @forelse($recentInvoices ?? [] as $invoice)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number ?? '#INV-000' }}</p>
                                <p class="text-xs text-gray-500">{{ $invoice->created_at?->format('M d, Y') ?? '-' }}</p>
                            </div>
                            <a href="/guest/invoices/{{ $invoice->id }}" class="text-blue-600 text-sm hover:text-blue-700">View</a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No invoices</p>
                    @endforelse
                </div>

                <a href="/guest/invoices" class="text-blue-600 text-sm mt-4 inline-block hover:text-blue-700">View all invoices →</a>
            </div>
        </div>
    </div>
</div>
@endsection
