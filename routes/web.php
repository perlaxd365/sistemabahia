<?php

use App\Http\Controllers\AtencionController;
use App\Http\Controllers\CajaChicaController;
use App\Http\Controllers\CajaMovimientoController;
use App\Http\Controllers\CajaTurnoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\FarmaciaController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SunatController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UsuarioController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Models\CajaChica;
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
    //ATENCION
    Route::get('atencion', [AtencionController::class, 'index'])->name('atencion');
    Route::get('atencion_general/{id}', [AtencionController::class, 'home'])->name('atencion_general');
    //PROVEEDOR
    Route::get('proveedor', [ProveedorController::class, 'index'])->name('proveedor');
    //FARMACIA
    Route::get('farmacia', [FarmaciaController::class, 'index'])->name('farmacia');
    //LABORATORIO
    Route::get('laboratorio/ordenes', [LaboratorioController::class, 'ordenes'])->name('laboratorio.ordenes');
    Route::get('laboratorio/ordenes/{id_orden}/resultados', [LaboratorioController::class, 'resultados'])->name('laboratorio.resultados');
    //IMAGEN
    Route::get('imagen/ordenes', [ImagenController::class, 'ordenes'])->name('imagen.ordenes');
    Route::get('imagen/ordenes/{id_orden}/resultados', [ImagenController::class, 'resultados'])->name('imagen.resultados');
    Route::get('/tickets/{comprobante}/imprimir', [TicketController::class, 'imprimir'])->name('tickets.imprimir');

    
    //PERFIL
    Route::get('perfil', [PerfilController::class, 'index'])->name('perfil');

    
    //CAJA
    Route::get('caja', [CajaTurnoController::class, 'index'])->name('caja');
    Route::get('cajachica', [CajaChicaController::class, 'index'])->name('cajachica');
    Route::get('cajamovimiento', [CajaMovimientoController::class, 'index'])->name('cajamovimiento');

    
    //SUNAT
    Route::get('consultarticket', [SunatController::class, 'consultarticket'])->name('consultarticket');
});
