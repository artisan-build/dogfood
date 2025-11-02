<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Livewire\SidebarNavigation;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->testDir = sys_get_temp_dir().'/agent-os-sidebar-test-'.uniqid();
    File::makeDirectory($this->testDir);
    File::makeDirectory("{$this->testDir}/.agent-os/product", 0755, true);
    File::makeDirectory("{$this->testDir}/.agent-os/specs/2025-11-02-test-spec", 0755, true);
    File::makeDirectory("{$this->testDir}/.agent-os/specs/2025-11-01-older-spec", 0755, true);

    File::put("{$this->testDir}/.agent-os/product/mission.md", '# Mission');
    File::put("{$this->testDir}/.agent-os/specs/2025-11-02-test-spec/spec.md", '# Test Spec');
    File::put("{$this->testDir}/.agent-os/specs/2025-11-01-older-spec/spec.md", '# Older Spec');
    File::put("{$this->testDir}/README.md", '# README');

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
    Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir])
        ->assertOk();
});

test('it includes Product folder link', function (): void {
    Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir])
        ->assertSee('Product');
});

test('it lists specs in reverse chronological order', function (): void {
    $component = Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir]);

    $items = $component->instance()->navigationItems;
    $specItems = collect($items)->where('type', 'spec')->values();

    expect($specItems[0]['label'])->toBe('Test Spec'); // 2025-11-02
    expect($specItems[1]['label'])->toBe('Older Spec'); // 2025-11-01
});

test('it includes README link', function (): void {
    Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir])
        ->assertSee('README');
});

test('it includes spec dates in navigation', function (): void {
    Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir])
        ->assertSee('2025-11-02')
        ->assertSee('2025-11-01');
});

test('it converts kebab-case spec names to Title Case', function (): void {
    Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir])
        ->assertSee('Test Spec')
        ->assertSee('Older Spec');
});

test('it marks active item based on current path', function (): void {
    $component = Livewire::test(SidebarNavigation::class, [
        'basePath' => $this->testDir,
        'currentPath' => '.agent-os/product',
    ]);

    $items = $component->instance()->navigationItems;
    $productItem = collect($items)->where('type', 'product')->first();

    expect($productItem['active'])->toBeTrue();
});

test('it handles missing product folder', function (): void {
    File::deleteDirectory("{$this->testDir}/.agent-os/product");

    $component = Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir]);

    $items = $component->instance()->navigationItems;
    $productItem = collect($items)->where('type', 'product')->first();

    expect($productItem)->toBeNull();
});

test('it handles missing README', function (): void {
    File::delete("{$this->testDir}/README.md");

    $component = Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir]);

    $items = $component->instance()->navigationItems;
    $readmeItem = collect($items)->where('type', 'readme')->first();

    expect($readmeItem)->toBeNull();
});

test('it includes additional configured directories', function (): void {
    File::makeDirectory("{$this->testDir}/docs", 0755, true);
    File::put("{$this->testDir}/docs/guide.md", '# Guide');

    config()->set('agent-os-installer.viewer.paths', [
        '.agent-os' => 'Agent OS Documentation',
        'docs' => 'User Documentation',
    ]);

    $component = Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir]);
    $items = $component->instance()->navigationItems;
    $docsItem = collect($items)->where('label', 'User Documentation')->first();

    expect($docsItem)->not->toBeNull();
});

test('it groups specs under a heading', function (): void {
    $component = Livewire::test(SidebarNavigation::class, ['basePath' => $this->testDir]);

    $items = $component->instance()->navigationItems;
    $specsHeading = collect($items)->where('type', 'heading')->where('label', 'Specs')->first();

    expect($specsHeading)->not->toBeNull();
});

test('it activates items matching current path', function (): void {
    $component = Livewire::test(SidebarNavigation::class, [
        'basePath' => $this->testDir,
        'currentPath' => '.agent-os/specs/2025-11-02-test-spec',
    ]);

    $items = $component->instance()->navigationItems;
    $specItems = collect($items)->where('type', 'spec');
    $activeItem = $specItems->where('active', true)->first();

    expect($activeItem['label'])->toBe('Test Spec');
});
