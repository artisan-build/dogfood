<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Services\DirectoryScanner;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    // Create test directory structure
    $this->testDir = base_path('tests/fixtures/.agent-os');
    File::ensureDirectoryExists($this->testDir.'/product');
    File::ensureDirectoryExists($this->testDir.'/specs/2025-11-01-test-spec');
    File::ensureDirectoryExists($this->testDir.'/specs/2025-10-15-older-spec');

    // Create test files
    File::put($this->testDir.'/product/mission.md', '# Mission');
    File::put($this->testDir.'/product/roadmap.md', '# Roadmap');
    File::put($this->testDir.'/specs/2025-11-01-test-spec/spec.md', '# Test Spec');
    File::put($this->testDir.'/specs/2025-10-15-older-spec/spec.md', '# Older Spec');

    // Create README.md
    File::put(base_path('tests/fixtures/README.md'), '# README');

    $this->scanner = new DirectoryScanner(base_path('tests/fixtures'));
});

afterEach(function (): void {
    File::deleteDirectory(base_path('tests/fixtures'));
});

it('scans agent-os directory recursively', function (): void {
    $structure = $this->scanner->scan();

    expect($structure)->toBeArray()
        ->and($structure['product'])->toBeArray()
        ->and($structure['specs'])->toBeArray();
});

it('includes README.md from project root', function (): void {
    $structure = $this->scanner->scan();

    expect($structure)->toHaveKey('readme')
        ->and($structure['readme']['path'])->toBe(base_path('tests/fixtures/README.md'));
});

it('parses spec folder names to extract date and title', function (): void {
    $structure = $this->scanner->scan();

    expect($structure['specs'])->toHaveCount(2)
        ->and($structure['specs'][0]['date'])->toBe('2025-11-01')
        ->and($structure['specs'][0]['title'])->toBe('Test Spec')
        ->and($structure['specs'][0]['folder'])->toBe('2025-11-01-test-spec');
});

it('sorts specs in reverse chronological order', function (): void {
    $structure = $this->scanner->scan();

    expect($structure['specs'][0]['date'])->toBe('2025-11-01')
        ->and($structure['specs'][1]['date'])->toBe('2025-10-15');
});

it('converts kebab-case spec names to Title Case', function (): void {
    $structure = $this->scanner->scan();

    expect($structure['specs'][0]['title'])->toBe('Test Spec');
});

it('handles missing directories gracefully', function (): void {
    $scanner = new DirectoryScanner(base_path('non-existent'));

    expect(fn () => $scanner->scan())->not->toThrow(Exception::class);
});

it('merges additional configured directories', function (): void {
    // Create additional test directory
    $docsDir = base_path('tests/fixtures/docs');
    File::ensureDirectoryExists($docsDir);
    File::put($docsDir.'/guide.md', '# Guide');

    config(['agent-os-installer.viewer.paths' => [
        '.agent-os' => 'Agent OS Documentation',
        'docs' => 'User Documentation',
    ]]);

    $structure = $this->scanner->scan();

    expect($structure)->toHaveKey('docs')
        ->and($structure['docs'])->toBeArray();
});
