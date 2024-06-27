<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function executeTransaction(User $user, User $authUser, Transaction $transaction): bool
    {
        return $transaction->user_id == $authUser->id && $user->isAdmin();
    }

    public function rejectTransaction(User $user, User $authUser, Transaction $transaction): bool
    {
        return $transaction->user_id == $authUser->id && $user->isAdmin();
    }
}
