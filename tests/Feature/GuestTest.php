<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Guest;

class GuestTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate --seed');
        $this->user = User::where('email', 'admin@hotel.local')->first();
    }

    public function test_can_list_guests()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/guests');

        $response->assertStatus(200)
            ->assertJsonIsArray();
    }

    public function test_can_create_guest()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '555-0100',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/guests', $data);

        $response->assertStatus(201)
            ->assertJsonPath('email', 'john@example.com');
    }

    public function test_can_show_guest()
    {
        $guest = Guest::first();
        
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/guests/{$guest->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $guest->id);
    }
}
