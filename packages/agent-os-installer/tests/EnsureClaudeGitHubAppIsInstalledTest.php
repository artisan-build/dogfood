<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

it('detects when Claude GitHub App is installed', function () {
    File::shouldReceive('exists')
        ->with(base_path('.github/claude.yml'))
        ->andReturn(true);

    File::shouldReceive('exists')
        ->with(base_path('.github/claude-code-review.yml'))
        ->andReturn(true);

    $this->artisan('agent-os:optimize-claude-reviews')
        ->expectsOutput('✓ Claude GitHub App is installed')
        ->assertExitCode(0);
});

it('fails when Claude GitHub App is not installed', function () {
    File::shouldReceive('exists')
        ->with(base_path('.github/claude.yml'))
        ->andReturn(false);

    File::shouldReceive('exists')
        ->with(base_path('.github/claude-code-review.yml'))
        ->andReturn(false);

    $this->artisan('agent-os:optimize-claude-reviews')
        ->expectsOutput('✗ Claude GitHub App is not installed')
        ->assertExitCode(1);
});

it('fails when only claude.yml exists', function () {
    File::shouldReceive('exists')
        ->with(base_path('.github/claude.yml'))
        ->andReturn(true);

    File::shouldReceive('exists')
        ->with(base_path('.github/claude-code-review.yml'))
        ->andReturn(false);

    $this->artisan('agent-os:optimize-claude-reviews')
        ->expectsOutput('✗ Claude GitHub App is not installed')
        ->assertExitCode(1);
});

it('fails when only claude-code-review.yml exists', function () {
    File::shouldReceive('exists')
        ->with(base_path('.github/claude.yml'))
        ->andReturn(false);

    File::shouldReceive('exists')
        ->with(base_path('.github/claude-code-review.yml'))
        ->andReturn(true);

    $this->artisan('agent-os:optimize-claude-reviews')
        ->expectsOutput('✗ Claude GitHub App is not installed')
        ->assertExitCode(1);
});
