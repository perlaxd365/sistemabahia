<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        Gate::define('admin', function ($user) {
            return $user->privilegio_cargo === 1;
        });
        //
        Gate::define('recepcion', function ($user) {
            return $user->privilegio_cargo >= 1;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
