<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Usuarios
    Route::get('/usuarios', [\App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{id}', [\App\Http\Controllers\Admin\UsuarioController::class, 'show'])->name('usuarios.show');
    Route::put('/usuarios/{id}', [\App\Http\Controllers\Admin\UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [\App\Http\Controllers\Admin\UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});
