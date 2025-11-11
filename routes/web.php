<?php

use Illuminate\Support\Facades\Route;

// health check
Route::get('/ping', function () {
	return response('OK', 200);
});

// Home
Route::get('/', [\App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');

// Contact
Route::get('/contacto', [\App\Http\Controllers\Web\ContactoController::class, 'send'])->name('contacto.send');

// Tours (example)
Route::get('/tours', [\App\Http\Controllers\Web\TourController::class, 'index'])->name('tours.index');

// Blog (Blade view)
Route::view('/blog', 'pages.blog')->name('blog');

// Include admin routes if present
if (file_exists(__DIR__ . '/admin.php')) {
	require __DIR__ . '/admin.php';
}