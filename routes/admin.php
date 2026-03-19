<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\ReservaController;

/**
 * Rutas administrativas
 *
 * Grupo seguro para la sección de administración. Se aplica middleware
 * `auth` y `can:admin` para asegurarse de que sólo usuarios autorizados
 * puedan acceder. Las rutas usan nombres con prefijo `admin.` para
 * facilitar el uso en las vistas y redirecciones.
 */
Route::middleware(['web', 'auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Panel principal del administrador
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Recursos CRUD
    // Usuarios: controlador con convenciones RESTful
    Route::resource('usuarios', UsuarioController::class);

    // Tours: gestión de tours (index, create, store, show, edit, update, destroy)
    Route::resource('tours', TourController::class);

    // Reservas: gestión de reservas (puede excluir acciones públicas si se desea)
    Route::resource('reservas', ReservaController::class);

    // Rutas adicionales útiles para admin (reportes, exports, etc.) pueden añadirse aquí
});

