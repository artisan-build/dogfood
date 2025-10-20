<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeClient;
    $this->mockClient = new MockClient;
});

test('background processes index returns list of background processes', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('background-processes-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->backgroundProcesses()->organizationsServersBackgroundProcessesIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filteruser: null,
        filtersiteId: null,
        filterdirectory: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(2)
        ->and($response->json('data.0.id'))->toBe('600')
        ->and($response->json('data.0.type'))->toBe('background-processes')
        ->and($response->json('data.0.attributes.command'))->toBe('php artisan queue:work --tries=3')
        ->and($response->json('data.0.attributes.user'))->toBe('forge')
        ->and($response->json('data.0.attributes.status'))->toBe('installed')
        ->and($response->json('data.1.id'))->toBe('601')
        ->and($response->json('data.1.attributes.command'))->toBe('php artisan horizon');
});

test('background processes show returns single background process', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('background-process-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->backgroundProcesses()->organizationsServersBackgroundProcessesShow('1', 100, 600);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('600')
        ->and($response->json('data.type'))->toBe('background-processes')
        ->and($response->json('data.attributes.command'))->toBe('php artisan queue:work --tries=3')
        ->and($response->json('data.attributes.user'))->toBe('forge')
        ->and($response->json('data.attributes.status'))->toBe('installed');
});

test('background processes show returns 404 for non-existent background process', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Background process'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->backgroundProcesses()->organizationsServersBackgroundProcessesShow('1', 100, 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('background processes destroy deletes a background process', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::make([], 204));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->backgroundProcesses()->organizationsServersBackgroundProcessesDestroy('1', 100, 600);

    expect($response->status())->toBe(204);
});

test('background processes index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->backgroundProcesses()->organizationsServersBackgroundProcessesIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filteruser: null,
        filtersiteId: null,
        filterdirectory: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
