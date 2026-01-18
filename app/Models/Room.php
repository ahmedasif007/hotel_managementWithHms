<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type_id',
        'status',
        'price_per_night',
        'floor',
        'notes',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function isAvailable($checkInDate, $checkOutDate)
    {
        return !$this->reservations()
            ->where('status', '!=', 'cancelled')
            ->whereBetween('check_out_date', [$checkInDate, $checkOutDate])
            ->orWhereBetween('check_in_date', [$checkInDate, $checkOutDate])
            ->exists();
    }

        public function isAvailableProperly($checkInDate, $checkOutDate)
        {
            return !$this->reservations()
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where('check_in_date', '<', $checkOutDate)
                          ->where('check_out_date', '>', $checkInDate);
                })
                ->exists();
        }
}
