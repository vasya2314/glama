<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClosingInvoice extends Model
{
    use HasFactory;

    protected $table = 'closing_invoices';
    protected $guarded = false;

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function closingDocument(): HasOne
    {
        return $this->hasOne(ClosingDocument::class);
    }
}
