<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Guest;

class GuestPolicy
{
    public function view(User $user)
    {
        return $user->hasPermission('view_guests');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_guest');
    }

    public function update(User $user, Guest $guest)
    {
        return $user->hasPermission('edit_guest');
    }

    public function delete(User $user, Guest $guest)
    {
        return $user->hasPermission('delete_guest');
    }
}
