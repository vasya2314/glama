<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;

class MessagePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if($user->isAdmin()) return true;
        return null;
    }

    public function viewAny(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id == $user->id;
    }

    public function create(User $user, Ticket $ticket): bool
    {
        return $ticket->assigned_to == $user->id || $ticket->user_id == $user->id;
    }

    public function update(User $user, Message $message): bool
    {
        return $message->user_id == $user->id;
    }

    public function delete(User $user, Message $message): bool
    {
        return $message->user_id == $user->id;
    }
}
