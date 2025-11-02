<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Services\AgentOsSearchService;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    $this->testDir = sys_get_temp_dir().'/agent-os-search-test-'.uniqid();
    File::makeDirectory($this->testDir);
    File::makeDirectory("{$this->testDir}/.agent-os/product", 0755, true);
    File::makeDirectory("{$this->testDir}/.agent-os/specs/2025-11-01-test-spec", 0755, true);

    // Create test files
    File::put("{$this->testDir}/.agent-os/product/mission.md", <<<'MD'
        # Product Mission

        This is a test product for searching functionality.
        It includes multiple keywords like Laravel, Livewire, and testing.
        MD);

    File::put("{$this->testDir}/.agent-os/product/roadmap.md", <<<'MD'
        # Roadmap

        Phase 1: Build the search feature
        Phase 2: Add more functionality
        MD);

    File::put("{$this->testDir}/.agent-os/specs/2025-11-01-test-spec/spec.md", <<<'MD'
        # Test Spec

        This spec describes how to implement the search functionality.
        Testing is important for quality assurance.
        MD);

    File::put("{$this->testDir}/README.md", <<<'MD'
        # README

        Project documentation and getting started guide.
        MD);

    config()->set('agent-os-installer.viewer.paths', [
        '.agent-os' => 'Agent OS Documentation',
    ]);
});

afterEach(function (): void {
    if (File::exists($this->testDir)) {
        File::deleteDirectory($this->testDir);
    }
});

test('it searches for a simple query', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('Laravel');

    expect($results)->toHaveCount(1);
    expect($results[0]['relative_path'])->toContain('mission.md');
    expect($results[0]['matches'])->toBe(1);
});

test('it returns empty array for empty query', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('');

    expect($results)->toBeArray()->toBeEmpty();
});

test('it returns empty array for whitespace query', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('   ');

    expect($results)->toBeArray()->toBeEmpty();
});

test('it performs case-insensitive search', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('LARAVEL');

    expect($results)->toHaveCount(1);
    expect($results[0]['relative_path'])->toContain('mission.md');
});

test('it searches across multiple files', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('Phase');

    expect($results)->toHaveCount(1);
    expect($results[0]['relative_path'])->toContain('roadmap.md');
});

test('it supports multi-word queries', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('implement search');

    // Both words must be present
    expect($results)->toHaveCount(1);
    expect($results[0]['relative_path'])->toContain('spec.md');
});

test('it supports phrase search with quotes', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('"search functionality"');

    expect($results)->toHaveCount(1);
    expect($results[0]['relative_path'])->toContain('spec.md');
});

test('it extracts context snippets', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('search');

    expect($results[0]['snippets'])->toBeArray();
    expect($results[0]['snippets'][0])->toHaveKey('line');
    expect($results[0]['snippets'][0])->toHaveKey('snippet');
});

test('it limits snippets to 3 per file', function (): void {
    // Create a file with many matches
    File::put("{$this->testDir}/.agent-os/product/test.md", <<<'MD'
        testing line 1
        testing line 2
        testing line 3
        testing line 4
        testing line 5
        MD);

    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('testing');

    $testMdResult = collect($results)->first(fn ($r) => str_contains((string) $r['relative_path'], 'test.md'));

    expect($testMdResult['matches'])->toBe(5);
    expect($testMdResult['snippets'])->toHaveCount(3);
});

test('it sorts results by match count', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('testing');

    // mission.md has "testing" once, spec.md has it twice
    expect($results[0]['matches'])->toBeGreaterThanOrEqual($results[1]['matches'] ?? 0);
});

test('it limits results to specified number', function (): void {
    // Create many test files
    for ($i = 1; $i <= 60; $i++) {
        File::put("{$this->testDir}/.agent-os/product/test-{$i}.md", "testing file {$i}");
    }

    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('testing', 50);

    expect($results)->toHaveCount(50);
});

test('it includes README.md in search', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('documentation');

    expect($results)->toHaveCount(1);
    expect($results[0]['relative_path'])->toBe('README.md');
    expect($results[0]['source'])->toBe('README');
});

test('it includes source directory in results', function (): void {
    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('Laravel');

    expect($results[0]['source'])->toBe('Agent OS Documentation');
});

test('it only searches markdown files', function (): void {
    File::put("{$this->testDir}/.agent-os/product/test.txt", 'This should not be searched');
    File::put("{$this->testDir}/.agent-os/product/test.php", '<?php // This should not be searched');

    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('searched');

    expect($results)->toBeEmpty();
});

test('it handles missing configured paths gracefully', function (): void {
    config()->set('agent-os-installer.viewer.paths', [
        '.agent-os' => 'Agent OS Documentation',
        'non-existent' => 'Missing Docs',
    ]);

    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('Laravel');

    expect($results)->toHaveCount(1); // Still finds results in .agent-os
});

test('it handles missing README gracefully', function (): void {
    File::delete("{$this->testDir}/README.md");

    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('Laravel');

    expect($results)->toHaveCount(1); // Still works without README
});

test('it truncates long snippets', function (): void {
    $longLine = str_repeat('word ', 100).'Laravel'.str_repeat(' word', 100);
    File::put("{$this->testDir}/.agent-os/product/long.md", $longLine);

    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('Laravel');

    $snippet = $results[0]['snippets'][0]['snippet'];
    expect(strlen((string) $snippet))->toBeLessThan(180); // 150 + ellipsis
    expect($snippet)->toContain('Laravel');
});

test('it adds ellipsis for truncated snippets', function (): void {
    $longLine = 'start '.str_repeat('word ', 50).'Laravel'.str_repeat(' word', 50).' end';
    File::put("{$this->testDir}/.agent-os/product/long.md", $longLine);

    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('Laravel');

    $snippet = $results[0]['snippets'][0]['snippet'];
    expect($snippet)->toContain('...');
});

test('it searches recursively in subdirectories', function (): void {
    File::makeDirectory("{$this->testDir}/.agent-os/specs/2025-11-01-test-spec/sub-specs", 0755, true);
    File::put("{$this->testDir}/.agent-os/specs/2025-11-01-test-spec/sub-specs/technical-spec.md", <<<'MD'
        # Technical Spec

        Deep nested file with Laravel content.
        MD);

    $service = new AgentOsSearchService($this->testDir);
    $results = $service->search('Deep nested');

    expect($results)->toHaveCount(1);
    expect($results[0]['relative_path'])->toContain('sub-specs/technical-spec.md');
});
