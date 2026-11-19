<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Auth::provider('legacy-eloquent', function ($app, array $config): LegacyUserProvider {
            return new LegacyUserProvider($app['hash'], $config['model']);
        });
    }
}
