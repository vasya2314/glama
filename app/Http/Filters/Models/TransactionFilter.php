<?php

namespace App\Http\Filters\Models;

use App\Http\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class TransactionFilter extends AbstractFilter
{
    public const ACCOUNT_NAME = 'type';
    public const DATE_START = 'date_start';
    public const DATE_END = 'date_end';
    public const METHOD_TYPE = 'method_type';

    protected function getCallbacks(): array
    {
        return [
            self::ACCOUNT_NAME => [$this, 'type'],
            self::DATE_START => [$this, 'dateStart'],
            self::DATE_END => [$this, 'dateEnd'],
            self::METHOD_TYPE => [$this, 'methodType'],
        ];
    }

    public function type(Builder $builder, $value): void
    {
        $builder->where('type', 'like', "%{$value}%");
    }

    public function methodType(Builder $builder, $value): void
    {
        $builder->where('method_type', 'like', "%{$value}%");
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
