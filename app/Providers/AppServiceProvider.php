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
        if (\Str::contains(\Config::get('app.url'), 'https://')) {
            \URL::forceRootUrl(config('app.url'));
            \URL::forceScheme('https');
        }
    }
}
