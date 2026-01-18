<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Create Room Types
        $single = RoomType::firstOrCreate(
            ['name' => 'Single Room'],
            [
                'description' => 'Comfortable single room with modern amenities',
                'base_price' => 50.00,
                'max_occupancy' => 1,
                'amenities' => ['WiFi', 'AC', 'Private Bathroom', 'TV'],
            ]
        );

        $double = RoomType::firstOrCreate(
            ['name' => 'Double Room'],
            [
                'description' => 'Spacious double room perfect for couples',
                'base_price' => 80.00,
                'max_occupancy' => 2,
                'amenities' => ['WiFi', 'AC', 'Private Bathroom', 'TV', 'Mini Bar'],
            ]
        );

        $suite = RoomType::firstOrCreate(
            ['name' => 'Suite'],
            [
                'description' => 'Luxury suite with premium facilities',
                'base_price' => 150.00,
                'max_occupancy' => 4,
                'amenities' => ['WiFi', 'AC', 'Private Bathroom', 'TV', 'Mini Bar', 'Living Area', 'Jacuzzi'],
            ]
        );

        // Create Sample Rooms
        for ($i = 1; $i <= 5; $i++) {
            Room::firstOrCreate(
                ['room_number' => "101-{$i}"],
                [
                    'room_type_id' => $single->id,
                    'status' => 'available',
                    'price_per_night' => 50.00,
                    'floor' => 1,
                ]
            );
        }

        for ($i = 1; $i <= 5; $i++) {
            Room::firstOrCreate(
                ['room_number' => "102-{$i}"],
                [
                    'room_type_id' => $double->id,
                    'status' => 'available',
                    'price_per_night' => 80.00,
                    'floor' => 1,
                ]
            );
        }

        for ($i = 1; $i <= 3; $i++) {
            Room::firstOrCreate(
                ['room_number' => "103-{$i}"],
                [
                    'room_type_id' => $suite->id,
                    'status' => 'available',
                    'price_per_night' => 150.00,
                    'floor' => 1,
                ]
            );
        }
    }
}
