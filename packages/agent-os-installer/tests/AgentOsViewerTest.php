<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Livewire\AgentOsViewer;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->testDir = sys_get_temp_dir().'/agent-os-viewer-test-'.uniqid();
    File::makeDirectory($this->testDir);
    File::makeDirectory("{$this->testDir}/.agent-os/product", 0755, true);
    File::makeDirectory("{$this->testDir}/.agent-os/specs/2025-11-02-test-spec", 0755, true);

    File::put("{$this->testDir}/.agent-os/product/mission.md", <<<'MD'
        # Product Mission

        This is the mission statement.
        MD);

    File::put("{$this->testDir}/.agent-os/product/roadmap.md", <<<'MD'
        # Roadmap

        Phase 1: Build the feature
        MD);

    File::put("{$this->testDir}/.agent-os/specs/2025-11-02-test-spec/spec.md", <<<'MD'
        # Test Spec

        This is a test specification.
        MD);

    File::put("{$this->testDir}/README.md", <<<'MD'
        # README

        Project readme content.
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

test('it renders successfully', function (): void {
    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir])
        ->assertOk();
});

test('it displays product documentation by default', function (): void {
    config()->set('agent-os-installer.viewer.default_view', 'product');

    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => ''])
        ->assertSee('Product Mission')
        ->assertSee('mission statement');
});

test('it displays README by default when configured', function (): void {
    config()->set('agent-os-installer.viewer.default_view', 'readme');

    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => ''])
        ->assertSee('Project readme content');
});

test('it displays product folder when path is .agent-os/product', function (): void {
    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => '.agent-os/product'])
        ->assertSee('Product Mission')
        ->assertSee('Roadmap');
});

test('it concatenates multiple product files', function (): void {
    $component = Livewire::test(AgentOsViewer::class, [
        'basePath' => $this->testDir,
        'path' => '.agent-os/product',
    ]);

    $html = $component->instance()->content;

    expect($html)->toContain('Product Mission');
    expect($html)->toContain('Roadmap');
});

test('it displays spec when path is a spec directory', function (): void {
    Livewire::test(AgentOsViewer::class, [
        'basePath' => $this->testDir,
        'path' => '.agent-os/specs/2025-11-02-test-spec',
    ])
        ->assertSee('Test Spec')
        ->assertSee('test specification');
});

test('it displays README when path is README.md', function (): void {
    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => 'README.md'])
        ->assertSee('Project readme content');
});

test('it displays individual markdown file', function (): void {
    File::put("{$this->testDir}/custom.md", '# Custom Doc');

    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => 'custom.md'])
        ->assertSee('Custom Doc');
});

test('it shows error message for missing product folder', function (): void {
    File::deleteDirectory("{$this->testDir}/.agent-os/product");

    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => '.agent-os/product'])
        ->assertSee('No product documentation found');
});

test('it shows error message for missing spec', function (): void {
    Livewire::test(AgentOsViewer::class, [
        'basePath' => $this->testDir,
        'path' => '.agent-os/specs/2025-11-02-nonexistent',
    ])
        ->assertSee('No spec documentation found');
});

test('it shows error message for missing README', function (): void {
    File::delete("{$this->testDir}/README.md");

    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => 'README.md'])
        ->assertSee('README.md not found');
});

test('it shows error message for missing file', function (): void {
    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => 'nonexistent.md'])
        ->assertSee('File not found');
});

test('it shows error message for non-markdown files', function (): void {
    File::put("{$this->testDir}/test.txt", 'Text file');

    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => 'test.txt'])
        ->assertSee('Only markdown files (.md) are supported');
});

test('it renders markdown to HTML', function (): void {
    File::put("{$this->testDir}/test.md", '# Heading');

    $component = Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir, 'path' => 'test.md']);
    $html = $component->instance()->content;

    expect($html)->toContain('<h1>Heading</h1>');
});

test('it includes sidebar navigation', function (): void {
    Livewire::test(AgentOsViewer::class, ['basePath' => $this->testDir])
        ->assertSeeLivewire('sidebar-navigation');
});

test('it passes current path to sidebar', function (): void {
    $component = Livewire::test(AgentOsViewer::class, [
        'basePath' => $this->testDir,
        'path' => '.agent-os/product',
    ]);

    // Check that sidebar receives the correct props
    expect($component->instance()->path)->toBe('.agent-os/product');
});

test('it converts @ reference links in product view', function (): void {
    File::put("{$this->testDir}/.agent-os/product/mission.md", <<<'MD'
        See @.agent-os/product/roadmap.md for details.
        MD);

    $component = Livewire::test(AgentOsViewer::class, [
        'basePath' => $this->testDir,
        'path' => '.agent-os/product',
    ]);

    $html = $component->instance()->content;
    expect($html)->toContain('href="/.agent-os/product/roadmap.md"');
});

test('it converts @ reference links in spec view', function (): void {
    File::put("{$this->testDir}/.agent-os/specs/2025-11-02-test-spec/spec.md", <<<'MD'
        See @.agent-os/product/mission.md for context.
        MD);

    $component = Livewire::test(AgentOsViewer::class, [
        'basePath' => $this->testDir,
        'path' => '.agent-os/specs/2025-11-02-test-spec',
    ]);

    $html = $component->instance()->content;
    expect($html)->toContain('href="/.agent-os/product/mission.md"');
});
