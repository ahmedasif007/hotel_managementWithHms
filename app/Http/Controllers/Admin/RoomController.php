<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms
     */
    public function index()
    {
        $rooms = Room::with('roomType', 'images')
            ->paginate(12);

        return view('admin.rooms.index', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * Show the form for creating a new room
     */
    public function create()
    {
        $roomTypes = RoomType::all();
        return view('admin.rooms.create', [
            'roomTypes' => $roomTypes,
        ]);
    }

    /**
     * Store a newly created room
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms',
            'room_type_id' => 'required|exists:room_types,id',
            'floor_number' => 'required|integer',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'size' => 'nullable|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified room
     */
    public function show(Room $room)
    {
        $room->load('roomType', 'images', 'reservations.guest');
        return view('admin.rooms.show', ['room' => $room]);
    }

    /**
     * Show the form for editing the room
     */
    public function edit(Room $room)
    {
        $roomTypes = RoomType::all();
        return view('admin.rooms.edit', [
            'room' => $room,
            'roomTypes' => $roomTypes,
        ]);
    }

    /**
     * Update the specified room
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id,
            'room_type_id' => 'required|exists:room_types,id',
            'floor_number' => 'required|integer',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'size' => 'nullable|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Delete the specified room
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
