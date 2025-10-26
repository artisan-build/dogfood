<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Exceptions\ProtectedResourceException;
use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeClient;
    $this->mockClient = new MockClient;
});

test('servers index returns list of servers', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('servers-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->servers()->organizationsServersIndex(
        organization: '1',
        sort: null,
        pagesize: null,
        pagecursor: null,
        filteripAddress: null,
        filtername: null,
        filterregion: null,
        filtersize: null,
        filterprovider: null,
        filterubuntuVersion: null,
        filterphpVersion: null,
        filterdatabaseType: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.id'))->toBe('100')
        ->and($response->json('data.0.type'))->toBe('servers')
        ->and($response->json('data.0.attributes.name'))->toBe('production-web-1')
        ->and($response->json('data.0.attributes.provider'))->toBe('ocean')
        ->and($response->json('data.0.attributes.ip_address'))->toBe('192.0.2.1');
});

test('servers show returns single server', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('server-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->servers()->organizationsServersShow('1', 100);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('100')
        ->and($response->json('data.type'))->toBe('servers')
        ->and($response->json('data.attributes.name'))->toBe('production-web-1')
        ->and($response->json('data.attributes.provider'))->toBe('ocean')
        ->and($response->json('data.attributes.database_type'))->toBe('mysql8')
        ->and($response->json('data.attributes.php_version'))->toBe('php83')
        ->and($response->json('data.attributes.is_ready'))->toBeTrue();
});

test('servers show returns 404 for non-existent server', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Server'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->servers()->organizationsServersShow('1', 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('servers destroy deletes a server', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::make([], 204));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->servers()->organizationsServersDestroy('1', 100);

    expect($response->status())->toBe(204);
});

test('servers destroy throws exception for protected server', function (): void {
    config(['forge-client.protected_servers' => [100]]);

    expect(fn () => $this->sdk->servers()->organizationsServersDestroy('1', 100))
        ->toThrow(
            ProtectedResourceException::class,
            'Cannot delete protected server (ID: 100)'
        );
});

test('servers destroy allows deletion of unprotected server when other servers are protected', function (): void {
    config(['forge-client.protected_servers' => [999, 888]]);

    $this->mockClient->addResponse(MockResponseFactory::make([], 204));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->servers()->organizationsServersDestroy('1', 100);

    expect($response->status())->toBe(204);
});

test('servers index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->servers()->organizationsServersIndex(
        organization: '1',
        sort: null,
        pagesize: null,
        pagecursor: null,
        filteripAddress: null,
        filtername: null,
        filterregion: null,
        filtersize: null,
        filterprovider: null,
        filterubuntuVersion: null,
        filterphpVersion: null,
        filterdatabaseType: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
