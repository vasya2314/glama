<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contract extends Model
{
    use HasFactory;

    const LEGAL_ENTITY = 'legal_entity';
    const INDIVIDUAL_ENTREPRENEUR = 'individual_entrepreneur';
    const NATURAL_PERSON = 'natural_person';

    protected $table = 'contracts';
    protected $guarded = false;

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function($contract)
        {
            $contract->contractable()->delete();
        });
    }

    protected function contractableType(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->castContractType($value),
        );
    }

    private function castContractType(string $string): string|null
    {
        return match ($string) {
            LegalEntity::class => self::LEGAL_ENTITY,
            IndividualEntrepreneur::class => self::INDIVIDUAL_ENTREPRENEUR,
            NaturalPerson::class => self::NATURAL_PERSON,
        };
    }

    public static function getAllTypes(): array
    {
        return [
            self::LEGAL_ENTITY,
            self::INDIVIDUAL_ENTREPRENEUR,
            self::NATURAL_PERSON,
        ];
    }

    public function contractable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

}
