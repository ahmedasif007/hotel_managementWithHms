<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardStatisticResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_rooms' => $this->get('total_rooms', 0),
            'available_rooms' => $this->get('available_rooms', 0),
            'occupied_rooms' => $this->get('occupied_rooms', 0),
            'total_guests' => $this->get('total_guests', 0),
            'current_reservations' => $this->get('current_reservations', 0),
            'total_revenue' => $this->get('total_revenue', 0),
            'pending_payments' => $this->get('pending_payments', 0),
        ];
    }
}
