@extends('layouts.app')

@section('title', 'Room Details')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="/rooms" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">← Back to Rooms</a>
        <h1 class="text-3xl font-bold text-gray-900">Room {{ $room->room_number ?? '#' }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Room Images -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="h-96 bg-gray-200 flex items-center justify-center">
                    @if($room->images->first())
                        <img src="{{ asset($room->images->first()->image_path) }}" alt="Room" class="w-full h-full object-cover">
                    @else
                        <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                        </svg>
                    @endif
                </div>
            </div>

            <!-- Room Details -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Details</h2>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-500 text-sm">Room Type</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $room->room_type->name ?? 'Standard' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Capacity</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $room->capacity ?? 2 }} guests</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Price per Night</p>
                        <p class="font-semibold text-gray-900 mt-1">${{ number_format($room->price_per_night ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Status</p>
                        <span class="inline-block mt-1 px-3 py-1 text-xs font-medium rounded-full
                            {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($room->status ?? 'unknown') }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-gray-500 text-sm">Description</p>
                    <p class="text-gray-900 mt-2">{{ $room->description ?? 'No description provided.' }}</p>
                </div>
            </div>

            <!-- Amenities -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Amenities</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    @php
                        $amenities = ['WiFi', 'Air Conditioning', 'TV', 'Minibar', 'Bathroom', 'Balcony', 'Safe', 'Work Desk'];
                    @endphp
                    @foreach($amenities as $amenity)
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">{{ $amenity }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Booking History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Booking History</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Guest</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Check-in</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Check-out</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($room->reservations ?? [] as $reservation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $reservation->guest->name ?? 'Guest' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->check_in_date?->format('M d, Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->check_out_date?->format('M d, Y') ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($reservation->status ?? 'pending') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">No bookings yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 sticky top-20">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-2">
                    @can('update-rooms')
                        <a href="/rooms/{{ $room->id }}/edit" class="w-full block bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700 transition">
                            Edit Room
                        </a>
                    @endcan
                    
                    @can('delete-rooms')
                        <form method="POST" action="/rooms/{{ $room->id }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full block bg-red-600 text-white px-4 py-2 rounded-lg text-center hover:bg-red-700 transition">
                                Delete Room
                            </button>
                        </form>
                    @endcan
                </div>

                <!-- Room Info Card -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-700 mb-3">Room Information</p>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Floor:</span>
                            <span class="font-medium text-gray-900">{{ $room->floor_number ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Size:</span>
                            <span class="font-medium text-gray-900">{{ $room->size ?? 'N/A' }} m²</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Beds:</span>
                            <span class="font-medium text-gray-900">{{ $room->bed_count ?? 1 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
