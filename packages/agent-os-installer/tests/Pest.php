<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Tests\TestCase;
use Illuminate\Support\Facades\File;

uses(TestCase::class)->in(__DIR__);

// Set up common mocks that all tests need
// Note: We do NOT set up Process::fake() here because each test has different
// Process expectations. Setting it up globally conflicts with test-specific mocks.
beforeEach(function (): void {
    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Allow File operations that may be needed
    File::shouldReceive('makeDirectory')->zeroOrMoreTimes();
    File::shouldReceive('copyDirectory')->zeroOrMoreTimes();
    File::shouldReceive('deleteDirectory')->zeroOrMoreTimes();
});
