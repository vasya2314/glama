<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\RewardContract;
use App\Models\User;

class RewardContractPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if($user->isAdmin()) return true;
        return null;
    }

    public function view(User $user, RewardContract $rewardContract): bool
    {
        return $rewardContract->user_id == $user->id;
    }

    public function update(User $user, RewardContract $rewardContract): bool
    {
        return $rewardContract->user_id == $user->id;
    }

    public function delete(User $user, RewardContract $rewardContract): bool
    {
        return $rewardContract->user_id == $user->id;
    }

}
