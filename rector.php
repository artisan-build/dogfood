<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/packages/*/src',
        __DIR__.'/packages/*/config',
        __DIR__.'/packages/*/resources',
        __DIR__.'/packages/*/tests',
        __DIR__.'/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/packages/turbulence/config/turbulence.php',
        __DIR__.'/packages/*/vendor',
        __DIR__.'/packages/*/vendor/**',
        '*/vendor/*',
        '**/vendor/**',
        // Skip converting $_SERVER to Request::server() in console context
        // Request::server() doesn't work in console/Artisan commands
        RectorLaravel\Rector\ArrayDimFetch\ServerVariableToRequestFacadeRector::class => [
            __DIR__.'/packages/agent-os-installer/src/Actions/EnsureAgentOsIsInstalled.php',
        ],
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
    ])
    ->withImportNames(true, false, true, true)
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
