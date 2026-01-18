<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::with('reservations')->get();
        return response()->json($guests);
    }

    public function show($id)
    {
        $guest = Guest::with('reservations.room', 'reservations.invoice')->findOrFail($id);
        return response()->json($guest);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:guests',
            'phone' => 'nullable|string',
            'id_number' => 'nullable|string|unique:guests',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $guest = Guest::create($validated);
        return response()->json($guest, 201);
    }

    public function update(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'email|unique:guests,email,' . $id,
            'phone' => 'nullable|string',
            'id_number' => 'nullable|string|unique:guests,id_number,' . $id,
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $guest->update($validated);
        return response()->json($guest);
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        return response()->json(['message' => 'Guest deleted']);
    }
}
