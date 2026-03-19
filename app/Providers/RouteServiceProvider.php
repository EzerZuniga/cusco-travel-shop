<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/**
 * ServiceProvider de rutas de la aplicación.
 *
 * Define bindings y la constante `HOME` usada por redirecciones después
 * de autenticación. Añadimos bindings seguros para modelos usados
 * frecuentemente (tour, reserva, usuario) si las clases existen.
 */

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Ruta de destino por defecto después del login.
     */
    public const HOME = '/';
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Registramos bindings de modelos de forma segura.
        try {
            if (class_exists(\App\Models\Tour::class)) {
                Route::model('tour', \App\Models\Tour::class);
            }
            if (class_exists(\App\Models\Reserva::class)) {
                Route::model('reserva', \App\Models\Reserva::class);
            }
            if (class_exists(\App\Models\Usuario::class)) {
                Route::model('usuario', \App\Models\Usuario::class);
            }
        } catch (\Throwable $e) {
            // No hacemos fallar el bootstrap por un binding; lo registramos en log.
            Log::warning('RouteServiceProvider: fallo al registrar bindings: ' . $e->getMessage());
        }

        // Carga las rutas si existen. Mantenemos compatibilidad con archivos separados.
        $this->routes(function () {
            if (file_exists(base_path('routes/web.php'))) {
                require base_path('routes/web.php');
            }

            if (file_exists(base_path('routes/api.php'))) {
                require base_path('routes/api.php');
            }
        });
    }
}
