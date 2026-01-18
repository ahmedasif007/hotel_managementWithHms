<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $checkIn = $this->faker->dateTimeBetween('now', '+30 days');
        $checkOut = (clone $checkIn)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');

        $guest = Guest::inRandomOrder()->first();
        $room = Room::inRandomOrder()->first();

        return [
            'guest_id' => $guest->id ?? 1,
            'room_id' => $room->id ?? 1,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'status' => 'confirmed',
            'number_of_guests' => $this->faker->numberBetween(1, 4),
            'notes' => $this->faker->optional()->text(100),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
