<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TourApiController;
use App\Http\Controllers\Api\ReservaApiController;

Route::prefix('v1')->group(function () {
    // Tours API
    Route::get('/tours', [TourApiController::class, 'index'])->name('api.tours.index');
    Route::get('/tours/{id}', [TourApiController::class, 'show'])->name('api.tours.show');
    
    // Reservas API
    Route::post('/reservas', [ReservaApiController::class, 'store'])->name('api.reservas.store');
    Route::get('/reservas/{id}', [ReservaApiController::class, 'show'])->name('api.reservas.show');
    Route::get('/reservas', [ReservaApiController::class, 'index'])->name('api.reservas.index');
});
