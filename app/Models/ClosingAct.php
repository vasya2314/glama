<?php

namespace App\Models;

use App\Observers\ClosingActObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([ClosingActObserver::class])]
class ClosingAct extends Model
{
    use HasFactory;

    protected $table = 'closing_acts';
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
