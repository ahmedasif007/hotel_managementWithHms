<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository extends BaseRepository
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    public function getAvailable($checkInDate, $checkOutDate, $columns = ['*'])
    {
        return $this->model
            ->whereNotIn('id', function($query) use ($checkInDate, $checkOutDate) {
                $query->select('room_id')
                    ->from('reservations')
                    ->where('status', '!=', 'cancelled')
                    ->where(function($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in_date', '<', $checkOutDate)
                            ->where('check_out_date', '>', $checkInDate);
                    });
            })
            ->where('status', 'available')
            ->get($columns);
    }

    public function getByType($roomTypeId, $columns = ['*'])
    {
        return $this->model->where('room_type_id', $roomTypeId)->get($columns);
    }

    public function getOccupied($columns = ['*'])
    {
        return $this->model->where('status', 'occupied')->get($columns);
    }
}
