<?php

namespace App\Models;

use App\Observers\TransactionObserver;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

#[ObservedBy([TransactionObserver::class])]
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

    const TYPE_DEPOSIT = 'deposit'; // Пополнение
    const TYPE_DEPOSIT_YANDEX_ACCOUNT = 'deposit_yandex_account'; // Вывод на яндекс
    const TYPE_REMOVAL = 'removal'; // Вывод средств из аккаунта

    const METHOD_TYPE_CARD = 'card';
    const METHOD_TYPE_QR = 'qr';
    const METHOD_TYPE_INVOICE = 'invoice';
    const METHOD_TYPE_CASHBACK = 'cashback';
    const METHOD_TYPE_RETURN = 'return';
    const METHOD_TYPE_TRANSFER = 'transfer';


    public static function getRefillsTypes(): array
    {
        return [
            self::TYPE_DEPOSIT,
        ];
    }

    public static function getRemovalTypes(): array
    {
        return [
            self::TYPE_DEPOSIT_YANDEX_ACCOUNT,
            self::TYPE_REMOVAL,
        ];
    }

    public static function getAllTypes(): array
    {
        return [
            self::TYPE_DEPOSIT,
            self::TYPE_DEPOSIT_YANDEX_ACCOUNT,
            self::TYPE_REMOVAL,
        ];
    }

    public static function generateUUID(): string
    {
        return Str::uuid()->toString();
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentClosingInvoice(): HasOne
    {
        return $this->hasOne(PaymentClosingInvoice::class);
    }

    public static function generateTransactionData(array $params = []): false|string
    {
        extract($params);

        $data = [];

        $data['accountNumber'] = $accountNumber ?? null;
        $data['pan'] = $pan ?? null;
        $data['name'] = $name ?? null;
        $data['reportId'] = $reportId ?? null;

        return json_encode($data);
    }

}
