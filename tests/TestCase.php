<?php

namespace Tests;

use ArtisanBuild\ClaudeCode\ClaudeCode;
use ArtisanBuild\ClaudeCode\Contracts\ClaudeCodeClient;
use ArtisanBuild\ClaudeCode\Tests\Mocks\MockClaudeCode;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Override;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     */
    #[Override]
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
