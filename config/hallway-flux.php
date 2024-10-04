<?php

declare(strict_types=1);

use Laravel\Jetstream\Http\Middleware\AuthenticateSession;

return [
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
