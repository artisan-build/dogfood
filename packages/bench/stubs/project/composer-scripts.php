<?php

return [
    'post-autoload-dump' => [
        'Illuminate\\Foundation\\ComposerScripts::postAutoloadDump',
        '@php artisan package:discover --ansi',
    ],
    'post-update-cmd' => [
        '@php artisan vendor:publish --tag=laravel-assets --ansi --force',
        '@php artisan ide-helper:generate',
        '@php artisan ide-helper:meta',
        '@php artisan ide-helper:models --write',
        '@php vendor/bin/pint',
    ],
    'test' => [
        '@php artisan test',
    ],
    'test-parallel' => [
        '@php artisan test --parallel --recreate-databases',
    ],
    'lint' => [
        'vendor/bin/pint',
    ],
    'stan' => [
        'vendor/bin/phpstan analyse --memory-limit=512M',
    ],
    'ready' => [
        '@php artisan ide-helper:models --write',
        'composer lint',
        'composer stan',
        'composer test',
    ],
    'coverage' => [
        'XDEBUG_MODE=coverage ./vendor/bin/pest --coverage-php coverage.php',
        '@php artisan generate-code-coverage-html',
    ],
];
