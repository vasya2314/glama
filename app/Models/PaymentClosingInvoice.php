<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentClosingInvoice extends Model
{
    use HasFactory;

    protected $guarded = false;
    protected $table = 'payment_closing_invoices';

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
