<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\ClosingDocument;
use App\Models\Contract;
use App\Models\User;

class ClosingDocumentPolicy
{
    public function before(User $user): bool|null
    {
        if($user->isAdmin()) return true;
        return null;
    }

    public function getClosingAct(User $user, ClosingDocument $closingDocument): bool
    {
        return $closingDocument->user_id == $user->id;
    }

    public function getClosingInvoice(User $user, ClosingDocument $closingDocument): bool
    {
        return $closingDocument->user_id == $user->id;
    }

}
