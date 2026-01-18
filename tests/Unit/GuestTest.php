<?php

namespace Tests\Unit;

use App\Models\Guest;
use Tests\TestCase;

class GuestTest extends TestCase
{
    public function test_guest_can_be_created()
    {
        $guest = Guest::factory()->create();

        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'email' => $guest->email,
        ]);
    }

    public function test_guest_has_valid_email()
    {
        $guest = Guest::factory()->create();

        $this->assertStringContainsString('@', $guest->email);
    }

    public function test_guest_has_phone_number()
    {
        $guest = Guest::factory()->create();

        $this->assertNotNull($guest->phone_number);
    }
}
