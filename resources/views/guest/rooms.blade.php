@extends('layouts.app')

@section('title', 'Browse Rooms')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Available Rooms</h1>
        <p class="text-gray-600 mt-2">Find and book your perfect room</p>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                <input type="date" name="check_in" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                <input type="date" name="check_out" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="suite">Suite</option>
                    <option value="deluxe">Deluxe</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Guests</label>
                <select name="capacity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Any</option>
                    <option value="1">1 Guest</option>
                    <option value="2">2 Guests</option>
                    <option value="4">4 Guests</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($availableRooms ?? [] as $room)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                <!-- Room Image -->
                <div class="h-48 bg-gray-200 flex items-center justify-center relative">
                    @if($room->images->first())
                        <img src="{{ asset($room->images->first()->image_path) }}" alt="Room" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                        </svg>
                    @endif
                    <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-semibold text-gray-900">
                        ${{ number_format($room->price_per_night ?? 0, 0) }}/night
                    </div>
                </div>

                <!-- Room Details -->
                <div class="p-6">
                    <div class="mb-2">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $room->room_type->name ?? 'Standard Room' }}</h3>
                        <p class="text-sm text-gray-600">Room {{ $room->room_number ?? '#' }}</p>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">{{ $room->description ?? 'Comfortable and well-appointed room' }}</p>

                    <!-- Amenities Preview -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 11a3 3 0 110-6 3 3 0 010 6zM9 3a6 6 0 100 12A6 6 0 009 3z"></path>
                            </svg>
                            {{ $room->capacity ?? 2 }} guests
                        </span>
                        @if($room->size)
                            <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $room->size }} m²
                            </span>
                        @endif
                    </div>

                    <!-- Book Button -->
                    <a href="/guest/reservations/create?room_id={{ $room->id }}" class="w-full block bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700 transition font-medium">
                        Book Now
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8-4m-8 4v10l8-4m0-10l-8-4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Available Rooms</h3>
                    <p class="text-gray-500">Try adjusting your search dates or room preferences</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
