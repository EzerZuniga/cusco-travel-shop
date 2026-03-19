<?php
<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

/*
|--------------------------------------------------------------------------
| Configuración de logging
|--------------------------------------------------------------------------
|
| Aquí definimos los canales de registro usados por la aplicación. El valor
| por defecto viene de `env('LOG_CHANNEL')`. Asegúrate de que esa clave
| tenga un valor válido en el `.env` para evitar errores en el bootstrap.
|
*/

return [

    'channels' => [
        // Canal 'stack' que combina varios canales (por defecto 'daily')
        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', env('LOG_STACK_CHANNELS', 'daily')),
            'ignore_exceptions' => env('LOG_IGNORE_EXCEPTIONS', false),

        ];
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => env('LOG_MAX_DAYS', 14),
        ],

        // Archivo único (útil en entornos simples)
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        // Registro en stderr (útil para contenedores)
        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        // Log al sistema (syslog)
        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        // Envía al error_log de PHP
        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        // Canal nulo (descarta logs)
        'null' => [
            'driver' => 'null',
        ],

        // Papertrail / otros servicios pueden añadirse aquí (opcional)
    ],
];
