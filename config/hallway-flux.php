<?php

declare(strict_types=1);

use Laravel\Jetstream\Http\Middleware\AuthenticateSession;

return [
    'community' => [
        'name' => 'Artisan Community',
        'logo_light' => 'https://artisan.build/img/logo.png',
        'logo_dark' => 'https://artisan.build/img/logo.png',
    ],
    'serves_welcome' => true,
    'route-prefix' => null,
    'route-name-prefix' => 'hallway-flux.',

    'middleware' => [
        'web',
        'auth:sanctum',
        // This works for us because we use Jetstream
        AuthenticateSession::class,
        'verified',
    ],
];
