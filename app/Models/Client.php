<?php

namespace App\Models;

use App\Jobs\EnableSharedAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $guarded = false;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    protected function casts(): array
    {
        return [
            'is_enable_shared_account' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

}
