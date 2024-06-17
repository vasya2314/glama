<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClosingAct extends Model
{
    use HasFactory;

    protected $table = 'closing_receipts';
    protected $guarded = false;

    public function clients(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
