<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';

    protected $table = 'tickets';
    protected $guarded = false;

    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_CLOSE,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

}
