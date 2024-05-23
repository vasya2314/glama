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

    public $timestamps = false;

    public static function isEnoughBalance(int $amount, User $user): bool
    {
        $balanceAccount = $user->balanceAccount;

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
