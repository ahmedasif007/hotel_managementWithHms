<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;

class GuestRoomController extends Controller
{
    /**
     * Display available rooms for guests
     */
    public function index(Request $request)
    {
        $query = Room::where('status', 'available')->with('roomType', 'images');

        // Filter by type
        if ($request->filled('type')) {
            $query->whereHas('roomType', function ($q) use ($request) {
                $q->where('name', $request->type);
            });
        }

        // Filter by capacity
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Check availability by dates
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            $query->whereDoesntHave('reservations', function ($q) use ($checkIn, $checkOut) {
                $q->where(function ($q) use ($checkIn, $checkOut) {
                    $q->whereBetween('check_in_date', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in_date', '<=', $checkIn)
                                ->where('check_out_date', '>=', $checkOut);
                        });
                })->whereNotIn('status', ['cancelled']);
            });
        }

        $availableRooms = $query->paginate(12);

        return view('guest.rooms', [
            'availableRooms' => $availableRooms,
        ]);
    }

    /**
     * Show room details
     */
    public function show(Room $room)
    {
        $room->load('roomType', 'images');
        return view('guest.rooms.show', ['room' => $room]);
    }
}
