<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPolicy extends Model
{
    public function create($user)
    {
        return $user->hasPermission('create_room');
    }

    public function update($user, $room)
    {
        return $user->hasPermission('edit_room');
    }

    public function delete($user, $room)
    {
        return $user->hasPermission('delete_room');
    }
}
