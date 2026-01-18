<?php

namespace App\Exceptions;

use Exception;

class RoomNotAvailableException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Room is not available for the selected dates',
            'error' => 'room_not_available',
        ], 422);
    }
}
