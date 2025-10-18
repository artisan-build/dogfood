<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Tests;

use ArtisanBuild\AgentOsInstaller\AgentOsInstallerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Base test case for Agent OS Installer package tests
 */
abstract class TestCase extends Orchestra
{
    /**
     * Get package providers
     */
    protected function getPackageProviders($app): array
    {
        return [
            AgentOsInstallerServiceProvider::class,
        ];
    }
}
