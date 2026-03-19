<?php

use Illuminate\Support\Facades\Route;

/**
 * Rutas web públicas
 * - Están cargadas dentro del middleware `web` por defecto
 */

// Health check simple
Route::get('/ping', function () {
	return response('OK', 200);
});

// Home
Route::get('/', [\App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');

// Contacto: form GET + envio POST (throttle para evitar spam)
Route::get('/contacto', [\App\Http\Controllers\Web\ContactoController::class, 'index'])->name('contacto.index');
Route::post('/contacto', [\App\Http\Controllers\Web\ContactoController::class, 'send'])
	->middleware('throttle:10,1')
	->name('contacto.send');

// Tours: listado y detalle (route-model binding recomendado en controlador)
Route::get('/tours', [\App\Http\Controllers\Web\TourController::class, 'index'])->name('tours.index');
Route::get('/tours/{tour}', [\App\Http\Controllers\Web\TourController::class, 'show'])
	->whereNumber('tour')
	->name('tours.show');

// Blog: listado y detalle usando BlogController
Route::get('/blog', [\App\Http\Controllers\Web\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [\App\Http\Controllers\Web\BlogController::class, 'show'])
	->whereNumber('post')
	->name('blog.show');

// Incluir rutas de administración si existen (archivo `routes/admin.php`)
if (file_exists(__DIR__ . '/admin.php')) {
	require __DIR__ . '/admin.php';
}

// Fallback: mostrar 404 amigable o redirigir al home
Route::fallback(function () {
	return response()->view('errors.404', [], 404);
});