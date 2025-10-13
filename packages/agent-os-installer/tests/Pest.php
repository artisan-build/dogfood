<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

uses(TestCase::class)->in(__DIR__);

// Set up common mocks that all tests need
beforeEach(function (): void {
    // Mock GitHub CLI check - all tests need this
    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
    ]);

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Allow File operations that may be needed
    File::shouldReceive('makeDirectory')->zeroOrMoreTimes();
    File::shouldReceive('copyDirectory')->zeroOrMoreTimes();
    File::shouldReceive('deleteDirectory')->zeroOrMoreTimes();
});
