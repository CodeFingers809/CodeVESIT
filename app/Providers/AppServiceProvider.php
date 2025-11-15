<?php

namespace App\Providers;

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
        // Session configuration:
        // - Normal sessions (without "remember me"): 1 day (set in config/session.php)
        // - Remember me sessions: Laravel's default is 5 years, but we'll use cookie lifetime
        // The remember_token is stored in the database and checked on each request
    }
}
