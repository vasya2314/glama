<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceAccount extends Model
{
    use HasFactory;

    protected $table = 'balance_accounts';
    protected $guarded = false;

    const BALANCE_MAIN = 'main';
    const BALANCE_REWARD = 'reward';

    public $timestamps = false;

    public static function getAllBalanceAccountsTypes(): array
    {
        return [
            self::BALANCE_REWARD,
            self::BALANCE_MAIN,
        ];
    }

    public static function isEnoughBalance(int $amount, User $user, string $type): bool
    {
        $balanceAccount = $user->balanceAccount($type)->firstOrFail();

        return (int)$balanceAccount->balance >= $amount;
    }

    public function increaseBalance(int $amount): bool
    {
        $balance = $this->balance + $amount;
        $this->balance = $balance;

        if($this->save()) return true;
        return false;
    }

    public function decreaseBalance(int $amount): bool
    {
        $balance = $this->balance - $amount;
        $this->balance = $balance;

        if($this->save()) return true;
        return false;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
