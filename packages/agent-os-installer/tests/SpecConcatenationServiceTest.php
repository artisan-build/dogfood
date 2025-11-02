<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Services\SpecConcatenationService;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Create test spec directory structure
    $this->testDir = base_path('tests/fixtures/.agent-os/specs/2025-11-01-test-spec');
    File::ensureDirectoryExists($this->testDir.'/sub-specs');

    // Create spec files
    File::put($this->testDir.'/spec.md', "# Spec Requirements Document\n\nThis is the spec.");
    File::put($this->testDir.'/sub-specs/technical-spec.md', "# Technical Specification\n\nThis is technical.");
    File::put($this->testDir.'/sub-specs/api-spec.md', "# API Specification\n\nThis is API.");
    File::put($this->testDir.'/sub-specs/tests.md', "# Tests Specification\n\nThis is tests.");
    File::put($this->testDir.'/tasks.md', "# Spec Tasks\n\nThese are tasks.");

    $this->service = new SpecConcatenationService(base_path('tests/fixtures'));
});

afterEach(function () {
    File::deleteDirectory(base_path('tests/fixtures'));
});

it('concatenates all files in spec directory', function () {
    $content = $this->service->concatenate('2025-11-01-test-spec');

    expect($content)->toContain('This is the spec.')
        ->and($content)->toContain('This is technical.')
        ->and($content)->toContain('This is API.')
        ->and($content)->toContain('This is tests.')
        ->and($content)->toContain('These are tasks.');
});

it('spec.md appears first', function () {
    $content = $this->service->concatenate('2025-11-01-test-spec');

    $specPos = strpos($content, 'This is the spec.');
    $technicalPos = strpos($content, 'This is technical.');

    expect($specPos)->toBeLessThan($technicalPos);
});

it('sub-specs are sorted alphabetically', function () {
    $content = $this->service->concatenate('2025-11-01-test-spec');

    $apiPos = strpos($content, 'This is API.');
    $technicalPos = strpos($content, 'This is technical.');
    $testsPos = strpos($content, 'This is tests.');

    expect($apiPos)->toBeLessThan($technicalPos)
        ->and($technicalPos)->toBeLessThan($testsPos);
});

it('tasks.md appears last', function () {
    $content = $this->service->concatenate('2025-11-01-test-spec');

    $testsPos = strpos($content, 'This is tests.');
    $tasksPos = strpos($content, 'These are tasks.');

    expect($testsPos)->toBeLessThan($tasksPos);
});

it('generates section headers from filenames', function () {
    $content = $this->service->concatenate('2025-11-01-test-spec');

    expect($content)->toContain('# Api Spec')
        ->and($content)->toContain('# Technical Spec')
        ->and($content)->toContain('# Tests')
        ->and($content)->toContain('# Tasks');
});

it('strips top-level headings from sub-files', function () {
    $content = $this->service->concatenate('2025-11-01-test-spec');

    expect($content)->not->toContain('# Technical Specification')
        ->and($content)->not->toContain('# API Specification')
        ->and($content)->not->toContain('# Tests Specification')
        ->and($content)->not->toContain('# Spec Tasks');
});

it('handles missing tasks.md gracefully', function () {
    File::delete($this->testDir.'/tasks.md');

    $content = $this->service->concatenate('2025-11-01-test-spec');

    expect($content)->toContain('This is the spec.')
        ->and($content)->not->toContain('These are tasks.');
});

it('returns empty string for non-existent spec', function () {
    $content = $this->service->concatenate('non-existent-spec');

    expect($content)->toBe('');
});
