<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    protected $roomType;
    protected $room;
    protected $guest;
    protected $reservation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roomType = RoomType::factory()->create();
        $this->room = Room::factory()->create(['room_type_id' => $this->roomType->id]);
        $this->guest = Guest::factory()->create();
        $this->reservation = Reservation::factory()->create([
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
        ]);
    }

    public function test_invoice_can_be_created()
    {
        $invoice = Invoice::factory()->create(['reservation_id' => $this->reservation->id]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'reservation_id' => $this->reservation->id,
        ]);
    }

    public function test_invoice_calculates_total_with_tax()
    {
        $invoice = Invoice::factory()->create([
            'reservation_id' => $this->reservation->id,
            'subtotal' => 100,
            'tax_percentage' => 10,
        ]);

        $expectedTotal = 100 + (100 * 0.10);
        $this->assertEquals($expectedTotal, $invoice->total);
    }
}
