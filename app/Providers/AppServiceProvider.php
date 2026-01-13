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
        Gate::define('Administrador', function ($user) {
            return $user->privilegio_cargo === 1;
        });
        //
        Gate::define('Doctor', function ($user) {
            return $user->privilegio_cargo === 2;
        });
        Gate::define('Enfermero', function ($user) {
            return $user->privilegio_cargo === 3;
        });
        Gate::define('Laboratorio', function ($user) {
            return $user->privilegio_cargo === 4;
        });
        Gate::define('Recepcionista', function ($user) {
            return $user->privilegio_cargo === 5;
        });
        Gate::define('Farmaceutico', function ($user) {
            return $user->privilegio_cargo === 6;
        });
        Gate::define('Paciente', function ($user) {
            return $user->privilegio_cargo === 7;
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
