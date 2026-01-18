<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'reservation' => new ReservationResource($this->reservation),
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'status' => $this->status,
            'issued_at' => $this->issued_at,
            'payments' => PaymentResource::collection($this->payments),
            'created_at' => $this->created_at,
        ];
    }
}
