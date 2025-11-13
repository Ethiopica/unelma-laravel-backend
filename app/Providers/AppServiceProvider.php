<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Observers\ContactMessageObserver;
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
    }
}
