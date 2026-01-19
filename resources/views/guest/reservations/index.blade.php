@extends('layouts.app')

@section('title', 'My Reservations')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Reservations</h1>
            <p class="text-gray-600 mt-2">View and manage your bookings</p>
        </div>
        <a href="/guest/rooms" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            + New Booking
        </a>
    </div>

    <!-- Reservations List -->
    <div class="space-y-4">
        @forelse($reservations ?? [] as $reservation)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                <div class="flex items-center justify-between p-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">Room {{ $reservation->room->room_number ?? '#' }}</h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reservation->status === 'checked_in') bg-blue-100 text-blue-800
                                @elseif($reservation->status === 'checked_out') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-3">{{ $reservation->room->room_type->name ?? 'Standard Room' }}</p>

                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Check-in</p>
                                <p class="font-semibold text-gray-900">{{ $reservation->check_in_date?->format('M d, Y') ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Check-out</p>
                                <p class="font-semibold text-gray-900">{{ $reservation->check_out_date?->format('M d, Y') ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Duration</p>
                                <p class="font-semibold text-gray-900">{{ $reservation->number_of_nights ?? 0 }} nights</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-right ml-4">
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($reservation->total_price ?? 0, 2) }}</p>
                        <div class="flex gap-2 mt-4">
                            <a href="/guest/reservations/{{ $reservation->id }}" class="text-blue-600 text-sm hover:text-blue-700">View</a>
                            @if($reservation->status === 'confirmed')
                                <form method="POST" action="/guest/reservations/{{ $reservation->id }}/cancel" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="text-red-600 text-sm hover:text-red-700" onclick="return confirm('Are you sure?')">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8-4m-8 4v10l8-4m0-10l-8-4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Reservations</h3>
                <p class="text-gray-500 mb-4">You haven't made any bookings yet</p>
                <a href="/guest/rooms" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
                    Browse Rooms
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
