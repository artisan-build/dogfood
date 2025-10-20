<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeClient;
    $this->mockClient = new MockClient;
});

test('commands index returns list of commands', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('commands-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->commands()->organizationsServersSitesCommandsIndex(
        organization: '1',
        server: 100,
        site: 200,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filteruserId: null,
        filterstatus: null,
        filtercommand: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(2)
        ->and($response->json('data.0.id'))->toBe('700')
        ->and($response->json('data.0.type'))->toBe('commands')
        ->and($response->json('data.0.attributes.command'))->toBe('php artisan migrate --force')
        ->and($response->json('data.0.attributes.user'))->toBe('forge')
        ->and($response->json('data.0.attributes.status'))->toBe('finished')
        ->and($response->json('data.0.attributes.exit_code'))->toBe(0)
        ->and($response->json('data.1.id'))->toBe('699')
        ->and($response->json('data.1.attributes.command'))->toBe('php artisan config:cache');
});

test('commands show returns single command', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('command-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->commands()->organizationsServersSitesCommandsShow('1', 100, 200, 700);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('700')
        ->and($response->json('data.type'))->toBe('commands')
        ->and($response->json('data.attributes.command'))->toBe('php artisan migrate --force')
        ->and($response->json('data.attributes.user'))->toBe('forge')
        ->and($response->json('data.attributes.status'))->toBe('finished')
        ->and($response->json('data.attributes.exit_code'))->toBe(0);
});

test('commands show returns 404 for non-existent command', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Command'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->commands()->organizationsServersSitesCommandsShow('1', 100, 200, 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('commands destroy deletes a command', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::make([], 204));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->commands()->organizationsServersSitesCommandsDestroy('1', 100, 200, 700);

    expect($response->status())->toBe(204);
});

test('commands index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->commands()->organizationsServersSitesCommandsIndex(
        organization: '1',
        server: 100,
        site: 200,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filteruserId: null,
        filterstatus: null,
        filtercommand: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
