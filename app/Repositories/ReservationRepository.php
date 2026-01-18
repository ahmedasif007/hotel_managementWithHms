<?php

namespace App\Repositories;

use App\Models\Reservation;

class ReservationRepository extends BaseRepository
{
    public function __construct(Reservation $model)
    {
        parent::__construct($model);
    }

    public function getByGuest($guestId, $columns = ['*'])
    {
        return $this->model->where('guest_id', $guestId)->get($columns);
    }

    public function getByRoom($roomId, $columns = ['*'])
    {
        return $this->model->where('room_id', $roomId)->get($columns);
    }

    public function getUpcoming($columns = ['*'])
    {
        return $this->model
            ->where('check_in_date', '>=', now())
            ->where('status', 'confirmed')
            ->get($columns);
    }

    public function getCheckedIn($columns = ['*'])
    {
        return $this->model->where('status', 'checked_in')->get($columns);
    }
}
