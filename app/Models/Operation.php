<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operations';
    protected $guarded = false;

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function paymentInvoice(): HasOne
    {
        return $this->hasOne(PaymentInvoice::class);
    }

}
