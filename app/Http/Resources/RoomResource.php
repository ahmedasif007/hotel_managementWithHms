<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'room_number' => $this->room_number,
            'room_type' => new RoomTypeResource($this->roomType),
            'status' => $this->status,
            'price_per_night' => $this->price_per_night,
            'floor' => $this->floor,
            'images' => RoomImageResource::collection($this->images),
            'created_at' => $this->created_at,
        ];
    }
}
