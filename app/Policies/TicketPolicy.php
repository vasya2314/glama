<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if($user->isAdmin()) return true;
        return null;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id == $user->id;
    }

    public function changeStatus(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id == $user->id;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id == $user->id;
    }
}
