<?php

namespace Tests\Performance;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\RoomType;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    protected $roomType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roomType = RoomType::factory()->create();
    }

    public function test_room_list_endpoint_performance()
    {
        // Create 100 rooms
        Room::factory(100)->create(['room_type_id' => $this->roomType->id]);

        $token = $this->getAuthToken();

        $startTime = microtime(true);
        $response = $this->getJson('/api/rooms', [
            'Authorization' => "Bearer $token"
        ]);
        $endTime = microtime(true);

        $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds

        $this->assertLess($duration, 500, "Room list endpoint took {$duration}ms, should be under 500ms");
        $this->assertLess($response->json('pagination.total'), 100);
    }

    public function test_availability_check_performance()
    {
        // Create 50 rooms and 50 reservations
        Room::factory(50)->create(['room_type_id' => $this->roomType->id]);
        $rooms = Room::all();
        $guest = Guest::factory()->create();

        $rooms->each(function ($room) use ($guest) {
            Reservation::factory()->create([
                'room_id' => $room->id,
                'guest_id' => $guest->id,
            ]);
        });

        $token = $this->getAuthToken();

        $startTime = microtime(true);
        $response = $this->getJson('/api/rooms/availability', [
            'Authorization' => "Bearer $token",
            'check_in_date' => now()->format('Y-m-d'),
            'check_out_date' => now()->addDays(3)->format('Y-m-d'),
        ]);
        $endTime = microtime(true);

        $duration = ($endTime - $startTime) * 1000;

        $this->assertLess($duration, 1000, "Availability check took {$duration}ms, should be under 1000ms");
    }

    public function test_dashboard_endpoint_performance()
    {
        // Create test data
        Room::factory(20)->create(['room_type_id' => $this->roomType->id]);
        Guest::factory(20)->create();
        Reservation::factory(20)->create();

        $token = $this->getAuthToken();

        $startTime = microtime(true);
        $response = $this->getJson('/api/dashboard/statistics', [
            'Authorization' => "Bearer $token"
        ]);
        $endTime = microtime(true);

        $duration = ($endTime - $startTime) * 1000;

        $this->assertLess($duration, 500, "Dashboard endpoint took {$duration}ms, should be under 500ms");
        $this->assertTrue($response['success']);
    }

    private function getAuthToken()
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        return explode('|', $token)[1];
    }
}
