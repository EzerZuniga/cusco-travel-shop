<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TourApiController;
use App\Http\Controllers\Api\ReservaApiController;
use App\Http\Controllers\Api\AuthController;

/**
 * API routes - versiónada (v1)
 * - Rutas públicas para consulta de tours
 * - Rutas protegidas (auth:sanctum) para creación/gestión de reservas
 * - Throttling aplicado para evitar abuso
 */
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Rutas públicas de autenticación
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    
    // Rutas públicas: consultar tours
    Route::get('tours', [TourApiController::class, 'index'])->name('tours.index');
    // Usamos route-model binding si el controlador lo soporta (param name: tour)
    Route::get('tours/{tour}', [TourApiController::class, 'show'])
        ->whereNumber('tour')
        ->name('tours.show');

    // Rutas protegidas: autenticación y reservas (usuario logueado vía sanctum)
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        // Rutas de autenticación protegidas
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
        
        // Rutas de reservas
        Route::post('reservas', [ReservaApiController::class, 'store'])->name('reservas.store');
        Route::get('reservas', [ReservaApiController::class, 'index'])->name('reservas.index');
        Route::get('reservas/{reserva}', [ReservaApiController::class, 'show'])
            ->whereNumber('reserva')
            ->name('reservas.show');
        Route::delete('reservas/{reserva}', [ReservaApiController::class, 'destroy'])
            ->whereNumber('reserva')
            ->name('reservas.destroy');
    });

    // Rutas de administración de API: ejemplo de punto protegido por permiso
    // (requiere Gate `admin` o policy configurada)
    Route::middleware(['auth:sanctum', 'can:admin', 'throttle:60,1'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // listado admin de reservas (opcional, implementado en el controller si existe)
            Route::get('reservas', [ReservaApiController::class, 'adminIndex'])->name('reservas.index');
        });
});

// Fallback para rutas API no encontradas (JSON)
Route::fallback(function () {
    return response()->json([
        'message' => 'API route not found.'
    ], 404);
});
