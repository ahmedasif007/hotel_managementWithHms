<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hotel.local',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hotel.local',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }
}
