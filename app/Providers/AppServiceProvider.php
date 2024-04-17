<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Message;
use App\Policies\ClientPolicy;
use App\Policies\MessagePolicy;
use App\Services\Core;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('Core', function ($app) {
            return Core::getInstance();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app('Core');

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $verifyUrl = env('VIEW_APP_URL') . '/verify-email-url/?url=' . $url;

            return (new MailMessage())
                ->subject(__('Verify Email Address'))
                ->line(__('Please click the button below to verify your email address.'))
                ->action(__('Verify Email Address'), $verifyUrl);
        });
    }
}
