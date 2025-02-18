<?php

declare(strict_types=1);


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
        'auth',
        'verified',
    ],
];
