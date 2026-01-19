@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Rooms</h1>
            <p class="text-gray-600 mt-2">Manage your hotel rooms and availability</p>
        </div>
        @can('create-rooms')
            <a href="/rooms/create" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                + Add Room
            </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="suite">Suite</option>
                    <option value="deluxe">Deluxe</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                <input type="range" class="w-full" min="0" max="1000" step="50">
            </div>
            <div class="flex items-end">
                <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Sample Room Cards -->
        @forelse($rooms ?? [] as $room)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                <!-- Room Image -->
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    @if($room->images->first())
                        <img src="{{ asset($room->images->first()->image_path) }}" alt="Room" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                        </svg>
                    @endif
                </div>

                <!-- Room Details -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-900">Room {{ $room->room_number ?? '#' }}</h3>
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($room->status ?? 'unknown') }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-3">{{ $room->room_type->name ?? 'Standard Room' }}</p>

                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-gray-500">Capacity</p>
                            <p class="font-semibold text-gray-900">{{ $room->capacity ?? 2 }} guests</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-gray-500">Price/Night</p>
                            <p class="font-semibold text-gray-900">${{ number_format($room->price_per_night ?? 0, 2) }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <a href="/rooms/{{ $room->id }}" class="flex-1 bg-blue-100 text-blue-600 px-3 py-2 rounded-lg text-center text-sm hover:bg-blue-200 transition">
                            View
                        </a>
                        @can('update-rooms')
                            <a href="/rooms/{{ $room->id }}/edit" class="flex-1 bg-gray-100 text-gray-600 px-3 py-2 rounded-lg text-center text-sm hover:bg-gray-200 transition">
                                Edit
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Rooms Found</h3>
                    <p class="text-gray-500 mb-4">Get started by adding your first room</p>
                    @can('create-rooms')
                        <a href="/rooms/create" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
                            Add Room
                        </a>
                    @endcan
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
