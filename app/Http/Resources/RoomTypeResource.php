<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_price' => $this->base_price,
            'max_occupancy' => $this->max_occupancy,
            'amenities' => $this->amenities,
        ];
    }
}
