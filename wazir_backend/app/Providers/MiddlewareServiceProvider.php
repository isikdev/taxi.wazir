<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use App\Http\Middleware\DispatcherAuth;
use App\Http\Middleware\DispatcherAuthMiddleware;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton('dispatcher.auth', function ($app) {
            return new DispatcherAuth();
        });
        
        App::singleton('disp.auth', function ($app) {
            return new DispatcherAuthMiddleware();
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
