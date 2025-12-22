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

    // 1. DASHBOARD

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. SERVICIOS (Admin)
    Route::resource('servicios', ServicioController::class);

    // 3. TÉCNICOS (Admin)
    Route::get('/tecnicos', [TecnicoController::class, 'index'])->name('tecnicos.index');
    Route::post('/tecnicos', [TecnicoController::class, 'store'])->name('tecnicos.store');
    Route::put('/tecnicos/{id}', [TecnicoController::class, 'update'])->name('tecnicos.update');


    // 4. PAGOS (Admin)
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');

    // NUEVA RUTA DE VALIDACIÓN
    Route::put('/pagos/{id}/validar', [PagoController::class, 'validar'])->name('pagos.validar');

    // 5. REPORTES Y CLIENTES (Admin)
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

    // Gestión de Clientes (Admin)
    Route::get('/clientes', [ClienteController::class, 'indexAdmin'])->name('clientes.index');
    Route::post('/clientes', [ClienteController::class, 'storeAdmin'])->name('clientes.store'); // Si agregaste este método
    // Si no tienes storeAdmin, usa la ruta que tenías antes o crea el método en el controlador
    // Ruta para la baja lógica (DELETE)
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::get('/mis-servicios/{id}', [App\Http\Controllers\ClienteController::class, 'show'])->name('cliente.servicios.show');

    // Gestión de Materiales (Admin)
    Route::get('/materiales', [MaterialController::class, 'index'])->name('materiales.index');
    Route::post('/materiales', [MaterialController::class, 'store'])->name('materiales.store');
    Route::put('/materiales/{id}', [MaterialController::class, 'update'])->name('materiales.update');

    // 6. ÁREA CLIENTE (Mi Cuenta)
    Route::get('/mi-cuenta', [ClienteController::class, 'index'])->name('cliente.index');
    Route::post('/mi-cuenta/solicitar', [ClienteController::class, 'store'])->name('cliente.servicios.store');

    // Completar perfil Cliente
    Route::get('/completar-perfil', [ClienteController::class, 'showCompleteProfile'])->name('cliente.complete.show');
    Route::post('/completar-perfil', [ClienteController::class, 'storeCompleteProfile'])->name('cliente.complete.store');

    // 7. ÁREA TÉCNICO (Panel de Trabajo)
    // ESTAS SON LAS RUTAS QUE FALTABAN O ESTABAN INCOMPLETAS
    Route::get('/tecnico', [TecnicoPanelController::class, 'index'])->name('tecnico.index');
    Route::delete('/tecnicos/{id}', [TecnicoController::class, 'destroy'])->name('tecnicos.destroy');
    Route::get('/tecnico/{id}', [TecnicoPanelController::class, 'show'])->name('tecnico.show');
    Route::put('/tecnico/{id}', [TecnicoPanelController::class, 'update'])->name('tecnico.update'); // <--- ESTA ES LA RUTA CRÍTICA
    Route::post('/tecnico/{id}/pago', [TecnicoPanelController::class, 'storePago'])->name('tecnico.pago.store');
});
