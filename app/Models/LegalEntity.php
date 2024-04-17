<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class LegalEntity extends Model
{
    use HasFactory;

    protected $table = 'legal_entities';
    protected $guarded = false;

    protected function casts(): array
    {
        return [
            'is_same_legal_address' => 'boolean',
        ];
    }

    public function contract(): MorphOne
    {
        return $this->morphOne(Contract::class, 'contractable');
    }
}
