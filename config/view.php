<?php

use Illuminate\Support\Env;

$base = dirname(__DIR__);

/*
|--------------------------------------------------------------------------
| Configuración de vistas
|--------------------------------------------------------------------------
|
| Aquí definimos las rutas donde buscar las plantillas Blade y la ubicación
| de los archivos compilados. Mantén `resources/views` como ruta por defecto
| y usa `VIEW_COMPILED_PATH` si necesitas personalizar el directorio compilado.
|
*/

return [
    /*
    |-----------------------------------------------------------------------
    | Rutas de almacenamiento de vistas
    |-----------------------------------------------------------------------
    |
    | Array de directorios donde Laravel buscará las vistas. Normalmente
    | se mantiene `resources/views`. Puedes añadir rutas adicionales si
    | organizas vistas por paquetes o módulos.
    |
    */
    'paths' => [
        resource_path('views'),
    ],

    /*
    |-----------------------------------------------------------------------
    | Ruta de vistas compiladas
    |-----------------------------------------------------------------------
    |
    | Los templates Blade se compilan a PHP y se guardan aquí. En entornos
    | productivos conviene garantizar permisos correctos y un sistema de
    | archivos rápido (o usar redis/opcache para optimizaciones adicionales).
    |
    */
    'compiled' => env('VIEW_COMPILED_PATH', storage_path('framework/views')),
];
