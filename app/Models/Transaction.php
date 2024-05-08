<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Znck\Eloquent\Traits\BelongsToThrough;

class Transaction extends Model
{
    use HasFactory, BelongsToThrough;

    protected $table = 'transactions';
    protected $guarded = false;

    public function operation(): HasOne
    {
        return $this->hasOne(Operation::class);
    }

    public function user(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(User::class, Operation::class);
    }

}
