<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\Tests;

use ArtisanBuild\OpencodeSdk\Providers\OpencodeSdkServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Get package providers.
     */
    protected function getPackageProviders($app): array
    {
        return [
            OpencodeSdkServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Setup default configuration
    }
}
