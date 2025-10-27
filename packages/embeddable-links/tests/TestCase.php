<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Tests;

use ArtisanBuild\EmbeddableLinks\Providers\EmbeddableLinksServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Override;

class TestCase extends Orchestra
{
    #[Override]
    protected function getPackageProviders($app): array
    {
        return [
            EmbeddableLinksServiceProvider::class,
        ];
    }

    #[Override]
    protected function getEnvironmentSetUp($app): void
    {
        config()->set('cache.default', 'array');
    }
}
