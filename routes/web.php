<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TecnicoPanelController;
use App\Http\Controllers\MaterialController;

// PÁGINA DE INICIO (Pública)
Route::get('/', function () {
    return view('welcome');
});

// GRUPO PROTEGIDO (Requiere Login)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // 1. DASHBOARD GLOBAL
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // --- ZONA ADMINISTRADOR ---

    // Servicios (CRUD Completo)
    Route::resource('servicios', ServicioController::class);

    // Técnicos (Gestión de Personal)
    Route::prefix('tecnicos')->name('tecnicos.')->group(function () {
        Route::get('/', [TecnicoController::class, 'index'])->name('index');
        Route::post('/', [TecnicoController::class, 'store'])->name('store');
        Route::put('/{id}', [TecnicoController::class, 'update'])->name('update');
        Route::delete('/{id}', [TecnicoController::class, 'destroy'])->name('destroy');
    });

    // Clientes (Gestión Administrativa) - ¡AQUÍ FALTABA EL PUT!
    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', [ClienteController::class, 'indexAdmin'])->name('index');
        Route::post('/', [ClienteController::class, 'storeAdmin'])->name('store');
        Route::put('/{id}', [ClienteController::class, 'updateAdmin'])->name('update'); // <--- CORRECCIÓN IMPORTANTE
        Route::delete('/{id}', [ClienteController::class, 'destroy'])->name('destroy');
    });

    // Materiales (Catálogo)
    Route::resource('materiales', MaterialController::class)->except(['create', 'edit', 'show']);

    // Pagos y Tesorería
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::put('/pagos/{id}/validar', [PagoController::class, 'validar'])->name('pagos.validar'); // Validar pago

    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');


    // --- ZONA CLIENTE ---
    Route::prefix('mi-cuenta')->name('cliente.')->group(function () {
        Route::get('/', [ClienteController::class, 'index'])->name('index');
        Route::post('/solicitar', [ClienteController::class, 'store'])->name('servicios.store');
        Route::get('/servicio/{id}', [ClienteController::class, 'show'])->name('servicios.show');

        // Completar Perfil (Primer uso)
        Route::get('/completar-perfil', [ClienteController::class, 'showCompleteProfile'])->name('complete.show');
        Route::post('/completar-perfil', [ClienteController::class, 'storeCompleteProfile'])->name('complete.store');
    });


    // --- ZONA TÉCNICO ---
    Route::prefix('tecnico')->name('tecnico.')->group(function () {
        Route::get('/', [TecnicoPanelController::class, 'index'])->name('index');
        Route::get('/{id}', [TecnicoPanelController::class, 'show'])->name('show');
        Route::put('/{id}', [TecnicoPanelController::class, 'update'])->name('update'); // Iniciar/Finalizar/Materiales
        Route::post('/{id}/pago', [TecnicoPanelController::class, 'storePago'])->name('pago.store'); // Cobrar en sitio
    });
});
