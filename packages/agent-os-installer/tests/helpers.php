<?php

declare(strict_types=1);

/**
 * Get a complete composer.json mock with ALL packages that ANY installation action checks for.
 * This prevents real composer commands from running during tests.
 */
function mockComposerJsonWithAllPackages(): array
{
    return [
        'require-dev' => [
            'pestphp/pest' => '^3.0',
            'pestphp/pest-plugin-laravel' => '^3.0',
            'laravel/pint' => '^1.0',
            'larastan/larastan' => '^3.0',
            'rector/rector' => '^2.0',
            'driftingly/rector-laravel' => '^2.0',
            'tightenco/duster' => '^3.0',
            'squizlabs/php_codesniffer' => '^3.0',
            'slevomat/coding-standard' => '^8.0',
            'dealerdirect/phpcodesniffer-composer-installer' => '^1.0',
            'ivqonsanada/enlightn' => '^3.0',
            'barryvdh/laravel-debugbar' => '^3.0',
            'barryvdh/laravel-ide-helper' => '^2.0',
        ],
    ];
}
