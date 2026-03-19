<?php

/*
|--------------------------------------------------------------------------
| Configuración de correo
|--------------------------------------------------------------------------
|
| Aquí se definen los mailers disponibles y los valores por defecto.
| Usamos variables de entorno para no guardar credenciales en el repo.
|
*/

return [
    // Mailer por defecto (smtp, sendmail, ses, mailgun, log, array)
    'default' => env('MAIL_MAILER', env('MAIL_DRIVER', 'smtp')),

    // Configuración de mailers. Añadir o adaptar según la pasarela usada.
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', null),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL', env('LOG_CHANNEL', 'stack')),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    // Direccion y nombre desde los cuales se envían correos por defecto
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', env('ADMIN_EMAIL', 'no-reply@example.com')),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Cusco Travel')),
    ],

    // Opciones para Markdown (si usas plantillas markdown para mails)
    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

    // Canal de log por defecto para mails (útil en desarrollo)
    'log_channel' => env('MAIL_LOG_CHANNEL', env('LOG_CHANNEL', 'stack')),
];
