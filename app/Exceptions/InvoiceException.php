<?php

namespace App\Exceptions;

use Exception;

class InvoiceException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => $this->message ?: 'Invoice operation failed',
            'error' => 'invoice_error',
        ], 422);
    }
}
