<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Services\ProductConcatenationService;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    // Create test directory structure
    $this->testDir = base_path('tests/fixtures/.agent-os/product');
    File::ensureDirectoryExists($this->testDir);

    // Create test files with headers
    File::put($this->testDir.'/mission.md', "# Product Mission\n\nThis is the mission.");
    File::put($this->testDir.'/roadmap.md', "# Product Roadmap\n\nThis is the roadmap.");
    File::put($this->testDir.'/tech-stack.md', "# Technical Stack\n\nThis is the tech stack.");
    File::put($this->testDir.'/decisions.md', "# Product Decisions Log\n\nThese are decisions.");

    $this->service = new ProductConcatenationService(base_path('tests/fixtures'));
});

afterEach(function (): void {
    File::deleteDirectory(base_path('tests/fixtures'));
});

it('concatenates all files in product folder', function (): void {
    $content = $this->service->concatenate();

    expect($content)->toBeString()
        ->and($content)->toContain('This is the mission.')
        ->and($content)->toContain('This is the roadmap.')
        ->and($content)->toContain('This is the tech stack.')
        ->and($content)->toContain('These are decisions.');
});

it('concatenates files in correct order', function (): void {
    $content = $this->service->concatenate();

    $missionPos = strpos((string) $content, 'This is the mission.');
    $roadmapPos = strpos((string) $content, 'This is the roadmap.');
    $techStackPos = strpos((string) $content, 'This is the tech stack.');
    $decisionsPos = strpos((string) $content, 'These are decisions.');

    expect($missionPos)->toBeLessThan($roadmapPos)
        ->and($roadmapPos)->toBeLessThan($techStackPos)
        ->and($techStackPos)->toBeLessThan($decisionsPos);
});

it('keeps mission.md heading but strips others', function (): void {
    $content = $this->service->concatenate();

    expect($content)->toContain('# Product Mission')
        ->and($content)->not->toContain('# Product Roadmap')
        ->and($content)->not->toContain('# Technical Stack')
        ->and($content)->not->toContain('# Product Decisions Log');
});

it('generates section headers from filenames', function (): void {
    $content = $this->service->concatenate();

    expect($content)->toContain('# Roadmap')
        ->and($content)->toContain('# Tech Stack')
        ->and($content)->toContain('# Decisions');
});

it('adds horizontal rules between sections', function (): void {
    $content = $this->service->concatenate();

    expect($content)->toContain('---');
});

it('handles missing product files gracefully', function (): void {
    // Remove one file
    File::delete($this->testDir.'/tech-stack.md');

    $content = $this->service->concatenate();

    expect($content)->toBeString()
        ->and($content)->toContain('This is the mission.')
        ->and($content)->toContain('This is the roadmap.')
        ->and($content)->not->toContain('This is the tech stack.');
});

it('returns empty string when product folder does not exist', function (): void {
    $service = new ProductConcatenationService(base_path('non-existent'));

    $content = $service->concatenate();

    expect($content)->toBe('');
});
