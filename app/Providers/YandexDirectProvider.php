<?php

namespace App\Providers;

use App\Classes\YandexDirect;
use Illuminate\Support\ServiceProvider;

class YandexDirectProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('yandexDirect',function(){
            return new YandexDirect();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
