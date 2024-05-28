<?php

namespace App\Http\Filters\Models;

use App\Http\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class ClientFilter extends AbstractFilter
{
    public const ACCOUNT_NAME = 'account_name';
    public const DATE_START = 'date_start';
    public const DATE_END = 'date_end';

    protected function getCallbacks(): array
    {
        return [
            self::ACCOUNT_NAME => [$this, 'accountName'],
            self::DATE_START => [$this, 'dateStart'],
            self::DATE_END => [$this, 'dateEnd'],
        ];
    }

    public function accountName(Builder $builder, $value): void
    {
        $builder->where('account_name', 'like', "%{$value}%");
    }

    public function dateStart(Builder $builder, $value): void
    {
        $builder->whereDate('created_at', '>=', $value);
    }

    public function dateEnd(Builder $builder, $value): void
    {
        $builder->whereDate('created_at', '<=', $value);
    }
}
