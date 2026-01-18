<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'guest' => new GuestResource($this->guest),
            'room' => new RoomResource($this->room),
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'number_of_nights' => $this->number_of_nights,
            'number_of_guests' => $this->number_of_guests,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'invoice' => new InvoiceResource($this->invoice),
            'created_at' => $this->created_at,
        ];
    }
}
