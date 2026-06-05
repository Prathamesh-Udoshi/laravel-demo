<?php

namespace App\Providers;

use App\Services\SmsService;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the service as a singleton
        $this->app->singleton(SmsService::class, function ($app) {
            // Retrieve configuration values from .env/config if needed
            $apiKey = config('services.sms.key', 'default_sms_key_12345');
            return new SmsService($apiKey);
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
