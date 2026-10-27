<?php

namespace Acsi\AuthCode;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthCodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/auth-code.php', 'auth-code');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'auth-code');
        $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/auth-code.php' => config_path('auth-code.php'),
            ], 'auth-code-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'auth-code-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/auth-code'),
            ], 'auth-code-views');
        }
    }
}