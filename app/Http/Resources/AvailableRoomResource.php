<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableRoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'room_number' => $this->room_number,
            'room_type' => new RoomTypeResource($this->whenLoaded('type')),
            'price_per_night' => $this->price_per_night,
            'status' => $this->status,
            'images' => RoomImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
