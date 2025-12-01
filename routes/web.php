<?php

use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UsuarioController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/index', function () {
        return view('login');
    })->name('index');
});

Route::group(['middleware' => ['auth']], function () {
    /**
     * Logout Route
     *//* 
    Route::get('/', [indexController::class, 'index'])->name('/'); */
    Route::redirect('/dashboard', '/index')->name('dashboard');

    //INICIO
    Route::get('index', [IndexController::class, 'index'])->name('index');
    //USUARIO
    Route::get('usuario', [UsuarioController::class, 'index'])->name('usuario');
    //CONFIGUACION
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion');

});