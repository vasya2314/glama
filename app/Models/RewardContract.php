<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RewardContract extends Model
{
    use HasFactory;

    protected $table = 'reward_contracts';
    protected $guarded = false;

    const LEGAL_ENTITY = 'legal_entity';
    const INDIVIDUAL_ENTREPRENEUR = 'individual_entrepreneur';
    const NATURAL_PERSON = 'natural_person';

    public static function getAllTypes(): array
    {
        return [
            self::LEGAL_ENTITY,
            self::INDIVIDUAL_ENTREPRENEUR,
            self::NATURAL_PERSON,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

}
