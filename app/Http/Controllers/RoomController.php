<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType', 'images')->get();
        return response()->json($rooms);
    }

    public function show($id)
    {
        $room = Room::with('roomType', 'images')->findOrFail($id);
        return response()->json($room);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Room::class);

        $validated = $request->validate([
            'room_number' => 'required|unique:rooms',
            'room_type_id' => 'required|exists:room_types,id',
            'price_per_night' => 'required|numeric|min:0',
            'floor' => 'nullable|integer',
            'status' => 'in:available,occupied,maintenance,reserved',
            'notes' => 'nullable|string',
        ]);

        $room = Room::create($validated);
        return response()->json($room, 201);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $this->authorize('update', $room);

        $validated = $request->validate([
            'status' => 'in:available,occupied,maintenance,reserved',
            'price_per_night' => 'numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $room->update($validated);
        return response()->json($room);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $this->authorize('delete', $room);

        $room->delete();
        return response()->json(['message' => 'Room deleted']);
    }

    public function availability(Request $request)
    {
        $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $rooms = Room::where('status', 'available')
            ->with('roomType')
            ->get()
            ->filter(fn ($room) => $room->isAvailable(
                $request->check_in_date,
                $request->check_out_date
            ));

        return response()->json($rooms);
    }

        public function availabilityOptimized(Request $request)
        {
            $request->validate([
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
            ]);

            $checkInDate = $request->check_in_date;
            $checkOutDate = $request->check_out_date;

            $rooms = Room::where('status', '!=', 'maintenance')
                ->with('roomType')
                ->whereDoesntHave('reservations', function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where('status', '!=', 'cancelled')
                          ->where('check_in_date', '<', $checkOutDate)
                          ->where('check_out_date', '>', $checkInDate);
                })
                ->get();

            return response()->json($rooms);
        }
}
