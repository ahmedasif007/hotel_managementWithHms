<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        $roomType = RoomType::inRandomOrder()->first();

        return [
            'room_number' => $this->faker->unique()->bothify('###-#'),
            'room_type_id' => $roomType->id ?? 1,
            'status' => $this->faker->randomElement(['available', 'occupied', 'maintenance']),
            'price_per_night' => $this->faker->randomFloat(2, 50, 300),
            'floor' => $this->faker->numberBetween(1, 10),
            'notes' => $this->faker->optional()->text(50),
        ];
    }
}
