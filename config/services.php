<?php

/*
|--------------------------------------------------------------------------
| Third Party Services
|--------------------------------------------------------------------------
|
| Aquí se colocan las credenciales para servicios externos como Stripe, Mailgun,
| Postmark, etc. Usar `env()` para no exponer claves en el repositorio.
|
*/

return [
    // Mailgun (env: MAILGUN_DOMAIN, MAILGUN_SECRET, MAILGUN_ENDPOINT)
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    // Postmark (env: POSTMARK_TOKEN)
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    // Amazon SES (env: AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION)
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Post-payment / payment gateway configuration
    'payment' => [
        // Gateway por defecto: 'mock' | 'stripe' | 'gateway_x'
        'gateway' => env('PAYMENT_GATEWAY', env('PAYMENT_DRIVER', 'mock')),
        // Modo de pruebas (true = sandbox/mock)
        'test_mode' => filter_var(env('PAYMENT_TEST', true), FILTER_VALIDATE_BOOLEAN),
        // Parámetros globales que puede necesitar una pasarela
        'currency' => env('PAYMENT_CURRENCY', 'USD'),
        'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
    ],

    // Stripe (env: STRIPE_KEY, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET)
    'stripe' => [
        'model' => App\Models\Usuario::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    // PayPal or other gateways can be added here
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'settings' => [
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => env('PAYPAL_LOG_LEVEL', 'ERROR'),
        ],
    ],

    // Webhook signing secrets for other services (opcional)
    'webhooks' => [
        'stripe' => env('STRIPE_WEBHOOK_SECRET'),
    ],
];

