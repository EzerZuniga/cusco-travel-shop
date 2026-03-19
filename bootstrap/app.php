<?php

use Illuminate\Foundation\Application;

/**
 * Bootstrap del framework
 *
 * Este archivo crea la instancia de la aplicación (IoC container) y
 * liga las implementaciones concretas de los Kernels y del manejador
 * de excepciones. Es un archivo estándar de Laravel y no suele requerir
 * cambios manuales; añadimos comentarios en español para claridad.
 */

$app = new Application(
    dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Se enlazan las interfaces más importantes con sus implementaciones
| concretas para que el contenedor pueda resolverlas cuando se necesiten.
| Estas clases deberían existir en `app/Http/Kernel.php`, `app/Console/Kernel.php`
| y `app/Exceptions/Handler.php`. Si alguna faltase, el arranque puede fallar,
| por lo que es recomendable ejecutar migraciones/instalación de dependencias.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
