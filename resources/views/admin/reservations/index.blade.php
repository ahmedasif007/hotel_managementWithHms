@extends('layouts.app')

@section('title', 'Reservations')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reservations</h1>
            <p class="text-gray-600 mt-2">Manage all booking reservations</p>
        </div>
        @can('create-reservations')
            <a href="/reservations/create" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                + New Reservation
            </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="checked_in">Checked In</option>
                    <option value="checked_out">Checked Out</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Guest Name</label>
                <input type="text" placeholder="Search guest..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Guest</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Room</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Check-in</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Check-out</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nights</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reservations ?? [] as $reservation)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $reservation->guest->name ?? 'Guest' }}</p>
                                    <p class="text-sm text-gray-500">{{ $reservation->guest->email ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">Room {{ $reservation->room->room_number ?? '#' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->check_in_date?->format('M d, Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->check_out_date?->format('M d, Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->number_of_nights ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">${{ number_format($reservation->total_price ?? 0, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($reservation->status === 'checked_in') bg-blue-100 text-blue-800
                                    @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $reservation->status ?? 'unknown')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="/reservations/{{ $reservation->id }}" class="text-blue-600 hover:text-blue-700">View</a>
                                @can('update-reservations')
                                    <a href="/reservations/{{ $reservation->id }}/edit" class="text-blue-600 hover:text-blue-700">Edit</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                No reservations found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
