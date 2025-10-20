<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeSdk;
    $this->mockClient = new MockClient;
});

// Database Schemas Tests

test('database schemas index returns list of database schemas', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('database-schemas-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseSchemasIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filtername: null,
        filterstatus: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(2)
        ->and($response->json('data.0.id'))->toBe('400')
        ->and($response->json('data.0.type'))->toBe('database-schemas')
        ->and($response->json('data.0.attributes.name'))->toBe('production_db')
        ->and($response->json('data.0.attributes.status'))->toBe('installed')
        ->and($response->json('data.1.id'))->toBe('401')
        ->and($response->json('data.1.attributes.name'))->toBe('staging_db');
});

test('database schemas show returns single database schema', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('database-schema-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseSchemasShow('1', 100, 400);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('400')
        ->and($response->json('data.type'))->toBe('database-schemas')
        ->and($response->json('data.attributes.name'))->toBe('production_db')
        ->and($response->json('data.attributes.status'))->toBe('installed');
});

test('database schemas show returns 404 for non-existent database schema', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Database schema'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseSchemasShow('1', 100, 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('database schemas destroy deletes a database schema', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::make([], 204));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseSchemasDestroy('1', 100, 400);

    expect($response->status())->toBe(204);
});

test('database schemas index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseSchemasIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filtername: null,
        filterstatus: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});

// Database Users Tests

test('database users index returns list of database users', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('database-users-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseUsersIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filtername: null,
        filterstatus: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(2)
        ->and($response->json('data.0.id'))->toBe('500')
        ->and($response->json('data.0.type'))->toBe('database-users')
        ->and($response->json('data.0.attributes.name'))->toBe('app_user')
        ->and($response->json('data.0.attributes.status'))->toBe('installed')
        ->and($response->json('data.0.attributes.databases'))->toBeArray()
        ->and($response->json('data.0.attributes.databases.0'))->toBe('production_db')
        ->and($response->json('data.1.id'))->toBe('501')
        ->and($response->json('data.1.attributes.name'))->toBe('staging_user');
});

test('database users show returns single database user', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('database-user-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseUsersShow('1', 100, 500);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('500')
        ->and($response->json('data.type'))->toBe('database-users')
        ->and($response->json('data.attributes.name'))->toBe('app_user')
        ->and($response->json('data.attributes.status'))->toBe('installed')
        ->and($response->json('data.attributes.databases'))->toBeArray()
        ->and($response->json('data.attributes.databases.0'))->toBe('production_db');
});

test('database users show returns 404 for non-existent database user', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Database user'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseUsersShow('1', 100, 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('database users destroy deletes a database user', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::make([], 204));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseUsersDestroy('1', 100, 500);

    expect($response->status())->toBe(204);
});

test('database users index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->databases()->organizationsServersDatabaseUsersIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filtername: null,
        filterstatus: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
