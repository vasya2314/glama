<?php

namespace App\Models;

 use App\Notifications\ResetPasswordNotification;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Database\Eloquent\Relations\HasMany;
 use Illuminate\Database\Eloquent\Relations\HasOne;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Notifications\Notifiable;
 use Laravel\Sanctum\HasApiTokens;
 use App\Observers\UserObserver;
 use Illuminate\Database\Eloquent\Attributes\ObservedBy;

 #[ObservedBy([UserObserver::class])]
 class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 0;
    const ROLE_USER = 1;

    const TYPE_SIMPLE = 'simple';
    const TYPE_AGENCY = 'agency';

    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'contact_email',
        'email',
        'password',
        'user_type',
        'parent_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function isAdmin(): bool
    {
        return $this->role == self::ROLE_ADMIN;
    }

    public function sendPasswordResetNotification($token): void
    {
        $params = [
            'token' => $token,
            'email' => request('email'),
        ];

        $url = env('VIEW_APP_URL') . '/reset-password?' . http_build_query($params);

        $this->notify(new ResetPasswordNotification($url));
    }

    public function accrueCashback(Report $report): float|int
    {
        $resultCost = 0;
        $data = (array)@json_decode($report->data);

        if($this->parent_id !== null)
        {
            $user = User::findOrFail($this->parent_id);
        } else {
            $user = $this;
        }

        $balanceAccount = $user->balanceAccount(BalanceAccount::BALANCE_REWARD)->lockForUpdate()->firstOrFail();

        if(!empty($data))
        {
            foreach($data as $login => $arrItems)
            {
                $resultCost += getClientAmountByReport($login, $report);
            }

            if($resultCost > 0)
            {
                $amountCashBack = $resultCost * env('YANDEX_CASHBACK_COEFFICIENT');
                $amountCashBack = rubToKop((float)$amountCashBack);
                $balanceAccount->increaseBalance($amountCashBack);

                $user->transactions()->create(
                    [
                        'type' => Transaction::TYPE_DEPOSIT,
                        'status' => Transaction::STATUS_EXECUTED,
                        'payment_id' => null,
                        'order_id' => Transaction::generateUUID(),
                        'amount_deposit' => $amountCashBack,
                        'amount' => $amountCashBack,
                        'data' => $report->data,
                        'method_type' => Transaction::METHOD_TYPE_CASHBACK,
                        'balance_account_type' => BalanceAccount::BALANCE_REWARD,
                    ]
                );
            }
        }

        return $resultCost;

    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

     public function rewardContracts(): HasMany
     {
         return $this->hasMany(RewardContract::class);
     }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function balanceAccount(string $type = null): HasOne
    {
        return $this->hasOne(BalanceAccount::class)->where('type', $type);
    }

    public function childUsers(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'parent_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function closingDocuments(): HasMany
    {
        return $this->hasMany(ClosingDocument::class);
    }

}
