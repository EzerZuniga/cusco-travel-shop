<?php

use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Configuración de sesión
|--------------------------------------------------------------------------
|
| Valores por defecto pensados para un entorno seguro pero desarrollable.
| Ajusta `SESSION_DRIVER`, `SESSION_LIFETIME` y `SESSION_SECURE_COOKIE`
| en tu archivo `.env` según el entorno (local / production).
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Controlador por defecto de sesiones
    |--------------------------------------------------------------------------
    |
    | Tipos soportados: "file", "cookie", "database", "apc", "memcached",
    | "redis", "dynamodb", "array". Usar `redis` o `database` en producción
    | cuando se necesita compartir sesiones entre múltiples instancias.
    |
    */
    'driver' => env('SESSION_DRIVER', 'file'),

    /* Duración (minutos) de la sesión inactiva antes de expirar */
    'lifetime' => env('SESSION_LIFETIME', 120),

    /* Cerrar sesión al cerrar el navegador */
    'expire_on_close' => filter_var(env('SESSION_EXPIRE_ON_CLOSE', false), FILTER_VALIDATE_BOOLEAN),

    /* Encriptar datos de sesión en la base storage (false por defecto) */
    'encrypt' => filter_var(env('SESSION_ENCRYPT', false), FILTER_VALIDATE_BOOLEAN),

    /* Directorio para sesiones cuando 'file' driver está activo */
    'files' => storage_path('framework/sessions'),

    /* Conexión de Redis si se usa 'redis' como driver */
    'connection' => env('SESSION_CONNECTION', null),

    /* Tabla para driver 'database' */
    'table' => env('SESSION_TABLE', 'sessions'),

    /* Store específico para drivers que lo soporten */
    'store' => env('SESSION_STORE', null),

    /* Probabilidad de garbage collection (numero de la lotería) */
    'lottery' => [
        (int)env('SESSION_LOTTERY_1', 2),
        (int)env('SESSION_LOTTERY_2', 100),
    ],

    /* Nombre de la cookie usada para la sesión */
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /* Ruta y dominio para la cookie de sesión */
    'path' => env('SESSION_PATH', '/'),
    'domain' => env('SESSION_DOMAIN', null),

    /* Forzar envío de cookie sólo por HTTPS en producción */
    'secure' => env('SESSION_SECURE_COOKIE', env('APP_ENV') === 'production' ? true : false),

    /* Sólo accesible via HTTP (no JS) */
    'http_only' => true,

    /* Política SameSite por defecto: 'lax' es un buen equilibrio */
    'same_site' => env('SESSION_SAME_SITE', 'lax'),
];
