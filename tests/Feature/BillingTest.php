<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Invoice;

class BillingTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate --seed');
        $this->user = User::where('email', 'admin@hotel.local')->first();
    }

    public function test_can_list_invoices()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/invoices');

        $response->assertStatus(200)
            ->assertJsonIsArray();
    }

    public function test_can_get_invoice()
    {
        $invoice = Invoice::first();
        
        if ($invoice) {
            $response = $this->actingAs($this->user, 'sanctum')
                ->getJson("/api/invoices/{$invoice->id}");

            $response->assertStatus(200)
                ->assertJsonPath('id', $invoice->id);
        }
    }

    public function test_can_record_payment()
    {
        $reservation = \App\Models\Reservation::first();
        
        if ($reservation) {
            $data = [
                'reservation_id' => $reservation->id,
                'amount' => 100.00,
                'payment_method' => 'card',
                'reference_number' => 'TXN-123456',
            ];

            $response = $this->actingAs($this->user, 'sanctum')
                ->postJson('/api/payments', $data);

            $response->assertStatus(201);
        }
    }
}
