<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;

class RoomTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate --seed');
        $this->user = User::where('email', 'admin@hotel.local')->first();
    }

    public function test_can_list_rooms()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/rooms');

        $response->assertStatus(200)
            ->assertJsonIsArray();
    }

    public function test_can_show_room()
    {
        $room = Room::first();
        
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/rooms/{$room->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $room->id);
    }

    public function test_can_check_availability()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/rooms/availability', [
                'check_in_date' => '2026-02-01',
                'check_out_date' => '2026-02-05',
            ]);

        $response->assertStatus(200)
            ->assertJsonIsArray();
    }
}
