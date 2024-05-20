<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class YandexDirect extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'yandexDirect';
    }
}
