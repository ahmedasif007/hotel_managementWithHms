<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reservation;

class ReservationTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate --seed');
        $this->user = User::where('email', 'admin@hotel.local')->first();
    }

    public function test_can_list_reservations()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/reservations');

        $response->assertStatus(200)
            ->assertJsonIsArray();
    }

    public function test_can_show_reservation()
    {
        $reservation = Reservation::first();
        
        if ($reservation) {
            $response = $this->actingAs($this->user, 'sanctum')
                ->getJson("/api/reservations/{$reservation->id}");

            $response->assertStatus(200)
                ->assertJsonPath('id', $reservation->id);
        }
    }

    public function test_cannot_create_invalid_reservation()
    {
        $data = [
            'guest_id' => 9999,
            'room_id' => 9999,
            'check_in_date' => '2026-02-01',
            'check_out_date' => '2026-02-05',
            'number_of_guests' => 1,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/reservations', $data);

        $response->assertStatus(422);
    }
}
