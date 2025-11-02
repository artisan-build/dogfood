<?php

declare(strict_types=1);

use ArtisanBuild\AgentOsInstaller\Http\Middleware\AgentOsViewerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function (): void {
    config()->set('agent-os-installer.viewer.gate', null);
});

test('it allows access in local environment', function (): void {
    app()->detectEnvironment(fn () => 'local');

    $middleware = new AgentOsViewerMiddleware;
    $request = Request::create('/test', 'GET');

    $response = $middleware->handle($request, fn ($req) => response('OK'));

    expect($response->getContent())->toBe('OK');
});

test('it allows access from localhost IP', function (): void {
    app()->detectEnvironment(fn () => 'production');

    $middleware = new AgentOsViewerMiddleware;
    $request = Request::create('/test', 'GET', [], [], [], ['REMOTE_ADDR' => '127.0.0.1']);

    $response = $middleware->handle($request, fn ($req) => response('OK'));

    expect($response->getContent())->toBe('OK');
});

test('it denies access in production without authentication', function (): void {
    app()->detectEnvironment(fn () => 'production');

    $middleware = new AgentOsViewerMiddleware;
    $request = Request::create('/test', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.1']);

    $middleware->handle($request, fn ($req) => response('OK'));
})->throws(HttpException::class, 'Authentication required');

test('it allows access in production with authenticated user', function (): void {
    app()->detectEnvironment(fn () => 'production');

    $user = new class
    {
        public function getAuthIdentifier()
        {
            return 1;
        }
    };

    $middleware = new AgentOsViewerMiddleware;
    $request = Request::create('/test', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.1']);
    $request->setUserResolver(fn () => $user);

    $response = $middleware->handle($request, fn ($req) => response('OK'));

    expect($response->getContent())->toBe('OK');
});

test('it uses custom gate when configured', function (): void {
    config()->set('agent-os-installer.viewer.gate', 'view-agent-os-docs');

    // Use Gate::before to bypass user requirements for this test
    Gate::before(fn () => true);

    $middleware = new AgentOsViewerMiddleware;
    $request = Request::create('/test', 'GET');

    $response = $middleware->handle($request, fn ($req) => response('OK'));

    expect($response->getContent())->toBe('OK');
});

test('it denies access when custom gate returns false', function (): void {
    config()->set('agent-os-installer.viewer.gate', 'view-agent-os-docs');

    Gate::define('view-agent-os-docs', fn () => false);

    $middleware = new AgentOsViewerMiddleware;
    $request = Request::create('/test', 'GET');

    $middleware->handle($request, fn ($req) => response('OK'));
})->throws(HttpException::class, 'Unauthorized to access Agent OS documentation');

test('it ignores missing gate gracefully', function (): void {
    config()->set('agent-os-installer.viewer.gate', 'non-existent-gate');

    app()->detectEnvironment(fn () => 'local');

    $middleware = new AgentOsViewerMiddleware;
    $request = Request::create('/test', 'GET');

    $response = $middleware->handle($request, fn ($req) => response('OK'));

    expect($response->getContent())->toBe('OK');
});
