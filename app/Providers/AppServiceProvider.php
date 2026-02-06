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

        //Farmacia 

        //ver
        Gate::define('ver-farmacia', function ($user) {
            return in_array($user->privilegio_cargo, [1,3,6]);
        });
        //editar 
        Gate::define('editar-farmacia', function ($user) {
            return in_array($user->privilegio_cargo,  [1,5, 6]);
        });

        //Laboratorio 

        //ver
        Gate::define('ver-laboratorio', function ($user) {
            return in_array($user->privilegio_cargo, [1,  4]);
        });
        Gate::define('editar-laboratorio', function ($user) {
            return in_array($user->privilegio_cargo,  [1, 4]);
        });

        //IMAGEN
        //ver
        Gate::define('ver-imagen', function ($user) {
            return in_array($user->privilegio_cargo, [1, 4]);
        });
        Gate::define('editar-imagen', function ($user) {
            return in_array($user->privilegio_cargo,  [1, 4]);
        });

        //CONFIGURACIONES
        //ver
        Gate::define('ver-configuracion', function ($user) {
            return in_array($user->privilegio_cargo, [1]);
        });
        //editar 
        Gate::define('editar-configuracion', function ($user) {
            return in_array($user->privilegio_cargo,  [1]);
        });

        //INICIO
        //ver
        Gate::define('ver-inicio', function ($user) {
            return in_array($user->privilegio_cargo, [1, 2, 3, 4, 5, 6]);
        });
        //editar 
        Gate::define('editar-inicio', function ($user) {
            return in_array($user->privilegio_cargo,  [1, 6]);
        });

        
        //RESULTADOS
        //ver
        Gate::define('ver-resultados', function ($user) {
            return in_array($user->privilegio_cargo, [1,2, 5]);
        });
        //editar 
        Gate::define('editar-resultados', function ($user) {
            return in_array($user->privilegio_cargo,  [1,2, 5]);
        });

         //CAJA
        //ver
        Gate::define('ver-caja', function ($user) {
            return in_array($user->privilegio_cargo, [1,5]);
        });
        //editar 
        Gate::define('editar-caja', function ($user) {
            return in_array($user->privilegio_cargo,  [1]);
        });

        
         //FUNCIONES VITALES
        //ver
        Gate::define('ver-signos', function ($user) {
            return in_array($user->privilegio_cargo, [1,2,5,3]);
        });
        //editar 
        Gate::define('editar-signos', function ($user) {
            return in_array($user->privilegio_cargo,  [1,2,3]);
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
