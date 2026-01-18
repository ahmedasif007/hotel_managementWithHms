<?php

namespace App\Exceptions;

use Exception;

class InvalidReservationException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => $this->message ?: 'Invalid reservation operation',
            'error' => 'invalid_reservation',
        ], 422);
    }
}
