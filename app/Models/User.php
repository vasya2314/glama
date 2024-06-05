<?php

namespace App\Models;

 use App\Notifications\ResetPasswordNotification;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Relations\HasMany;
 use Illuminate\Database\Eloquent\Relations\HasOne;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Notifications\Notifiable;
 use Laravel\Sanctum\HasApiTokens;

 class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 0;
    const ROLE_USER = 1;

    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'contact_email',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public static function boot(): void
    {
         parent::boot();

         static::created(function($user)
         {
             $user->balanceAccount()->create(
                 [
                     'balance' => 0,
                     'type' => 'main',
                 ]
             );
         });
    }

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

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function balanceAccount(): HasOne
    {
        return $this->hasOne(BalanceAccount::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

}
