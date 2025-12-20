<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Observers\ContactMessageObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ContactMessage::observe(ContactMessageObserver::class);

        // Force HTTPS in production (Railway, Heroku, etc.)
        // This ensures all generated URLs use https://
        if ($this->app->environment('production') || 
            str_starts_with(config('app.url'), 'https://') ||
            request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }
    }
}
