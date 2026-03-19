<?php

/*
|--------------------------------------------------------------------------
| Configuración de la aplicación
|--------------------------------------------------------------------------
|
| Este archivo contiene los valores principales de configuración de la
| aplicación (nombre, entorno, zona horaria, proveedores, etc.). Se añaden
| algunas claves útiles como `admin_email` que se usa en AuthServiceProvider.
|
*/

return [
    'name' => env('APP_NAME', 'Cusco Travel'),
    'env' => env('APP_ENV', 'local'),
    'key' => env('APP_KEY'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost'),
    // Zona horaria por defecto. Puedes cambiarla a 'America/Lima' si lo prefieres.
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => env('APP_LOCALE', 'es'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'es'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'es_PE'),

    // Correo del administrador (usado como fallback en algunas comprobaciones)
    'admin_email' => env('ADMIN_EMAIL', null),

    // Cifrado
    'cipher' => env('APP_CIPHER', 'AES-256-CBC'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */
    'providers' => [
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Illuminate\Routing\RoutingServiceProvider::class,
        // Core services often required by the framework
        Illuminate\Events\EventServiceProvider::class,
        Illuminate\Log\LogServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        /* Application Service Providers */
        App\Providers\RouteServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\RepositoryServiceProvider::class,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Aliases
    |--------------------------------------------------------------------------
    |
    | Aquí puedes registrar los alias de las facades que uses frecuentemente.
    |
    */
    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
    ],
];
