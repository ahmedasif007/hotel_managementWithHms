<?php

namespace Tests\Unit;

use App\Models\Room;
use App\Models\RoomType;
use Tests\TestCase;

class RoomTest extends TestCase
{
    protected $roomType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roomType = RoomType::factory()->create();
    }

    public function test_room_can_be_created()
    {
        $room = Room::factory()->create(['room_type_id' => $this->roomType->id]);

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'room_number' => $room->room_number,
        ]);
    }

    public function test_room_has_type_relationship()
    {
        $room = Room::factory()->create(['room_type_id' => $this->roomType->id]);

        $this->assertInstanceOf(RoomType::class, $room->type);
        $this->assertEquals($this->roomType->id, $room->type->id);
    }

    public function test_room_status_defaults_to_available()
    {
        $room = Room::factory()->create(['room_type_id' => $this->roomType->id]);

        $this->assertEquals('available', $room->status);
    }
}
