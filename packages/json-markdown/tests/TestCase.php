<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown\Tests;

use ArtisanBuild\JsonMarkdown\Providers\JsonMarkdownServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            JsonMarkdownServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Configure any test-specific environment settings
        $app['config']->set('json-markdown.pretty_print', true);
    }
}
