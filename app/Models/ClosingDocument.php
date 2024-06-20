<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClosingDocument extends Model
{
    use HasFactory;

    protected $table = 'closing_documents';
    protected $guarded = false;

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function closingAct(): BelongsTo
    {
        return $this->belongsTo(ClosingAct::class);
    }

    public function closingInvoice(): BelongsTo
    {
        return $this->belongsTo(ClosingInvoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
