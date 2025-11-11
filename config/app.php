<?php

return [
    'name' => env('APP_NAME', 'Cusco Travel'),
    'env' => env('APP_ENV', 'local'),
    'key' => env('APP_KEY'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => 'es',

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
        /* Application Service Providers */
        App\Providers\RouteServiceProvider::class,
    ],
];
