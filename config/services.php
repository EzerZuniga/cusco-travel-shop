<?php

return [
    // Add third-party service credentials here (Mailgun, SES, etc.)
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],
];
