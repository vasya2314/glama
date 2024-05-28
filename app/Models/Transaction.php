<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, Filterable;

    protected $table = 'transactions';
    protected $guarded = false;

    const STATUS_NEW = 'NEW';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_PREAUTHORIZING = 'PREAUTHORIZING';
    const STATUS_FORM_SHOWED = 'FORM_SHOWED';
    const STATUS_AUTHORIZED = 'AUTHORIZED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_REVERSED = 'REVERSED';
    const STATUS_REFUNDED = 'REFUNDED';
    const STATUS_DEADLINE_EXPIRED = 'DEADLINE_EXPIRED';
    const STATUS_PARTIAL_REFUNDED = 'PARTIAL_REFUNDED';
    const STATUS_PARTIAL_REVERSED = 'PARTIAL_REVERSED';
    const STATUS_CONFIRMED = 'CONFIRMED';

    const STATUS_SUBMITTED = 'SUBMITTED';
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_EXECUTED = 'EXECUTED';


    const TYPE_DEPOSIT = 'deposit';
    const TYPE_DEPOSIT_INVOICE = 'deposit_invoice';
    const TYPE_DEPOSIT_YANDEX_ACCOUNT = 'deposit_yandex_account';
    const TYPE_REMOVAL = 'removal';
    const TYPE_CASHBACK = 'cashback';

    public static function getAllTypes(): array
    {
        return [
            self::TYPE_DEPOSIT,
            self::TYPE_DEPOSIT_INVOICE,
            self::TYPE_DEPOSIT_YANDEX_ACCOUNT,
            self::TYPE_REMOVAL,
            self::TYPE_CASHBACK,
        ];
    }

    public static function generateUUID(): string
    {
        return Str::uuid()->toString();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
