<?php

declare(strict_types=1);

it('has viewer configuration with default values', function (): void {
    $config = config('agent-os-installer.viewer');

    expect($config)->toBeArray()
        ->and($config['enabled'])->toBeTrue()
        ->and($config['route_prefix'])->toBe('agent-os')
        ->and($config['middleware'])->toBe(['web'])
        ->and($config['gate'])->toBeNull()
        ->and($config['paths'])->toBeArray()
        ->and($config['paths']['.agent-os'])->toBe('Agent OS Documentation')
        ->and($config['default_view'])->toBe('product');
});

it('respects AGENT_OS_VIEWER_ENABLED environment variable', function (): void {
    config(['agent-os-installer.viewer.enabled' => false]);

    expect(config('agent-os-installer.viewer.enabled'))->toBeFalse();
});

it('respects AGENT_OS_ROUTE_PREFIX environment variable', function (): void {
    config(['agent-os-installer.viewer.route_prefix' => 'documentation']);

    expect(config('agent-os-installer.viewer.route_prefix'))->toBe('documentation');
});

it('allows custom middleware configuration', function (): void {
    config(['agent-os-installer.viewer.middleware' => ['web', 'auth']]);

    expect(config('agent-os-installer.viewer.middleware'))
        ->toBeArray()
        ->toHaveCount(2)
        ->toContain('web', 'auth');
});

it('allows custom gate configuration', function (): void {
    config(['agent-os-installer.viewer.gate' => 'view-agent-os']);

    expect(config('agent-os-installer.viewer.gate'))->toBe('view-agent-os');
});

it('allows additional paths configuration', function (): void {
    config([
        'agent-os-installer.viewer.paths' => [
            '.agent-os' => 'Agent OS Documentation',
            'docs' => 'User Documentation',
        ],
    ]);

    $paths = config('agent-os-installer.viewer.paths');

    expect($paths)
        ->toBeArray()
        ->toHaveCount(2)
        ->and($paths['.agent-os'])->toBe('Agent OS Documentation')
        ->and($paths['docs'])->toBe('User Documentation');
});

it('allows custom default_view configuration', function (): void {
    config(['agent-os-installer.viewer.default_view' => 'readme']);

    expect(config('agent-os-installer.viewer.default_view'))->toBe('readme');
});
