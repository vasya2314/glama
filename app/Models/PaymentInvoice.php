<?php

namespace App\Models;

use App\Jobs\SendPaymentInvoiceToEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Support\Facades\Mail;

class PaymentInvoice extends Model
{
    use HasFactory, BelongsToThrough;

    protected $table = 'payment_invoices';
    protected $guarded = false;

    const STATUS_SUBMITTED = 'SUBMITTED';
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_EXECUTED = 'EXECUTED';

    public static function boot(): void
    {
        parent::boot();

        static::created(function($paymentInvoice)
        {
            dispatch(new SendPaymentInvoiceToEmail($paymentInvoice, request()->user()));
        });
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

    public function user(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(User::class, Operation::class);
    }

}
