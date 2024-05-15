<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

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

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_DEPOSIT_INVOICE = 'deposit_invoice';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
