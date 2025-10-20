<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeClient;
    $this->mockClient = new MockClient;
});

test('sites index returns list of sites for a server', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('sites-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->sites()->organizationsServersSitesIndex(
        organization: '1',
        server: 100,
        sort: null,
        include: null,
        pagesize: null,
        pagecursor: null,
        filtername: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(2)
        ->and($response->json('data.0.id'))->toBe('200')
        ->and($response->json('data.0.type'))->toBe('sites')
        ->and($response->json('data.0.attributes.name'))->toBe('example.com')
        ->and($response->json('data.0.attributes.repository'))->toBe('user/repo')
        ->and($response->json('data.0.attributes.repository_branch'))->toBe('main')
        ->and($response->json('data.0.attributes.quick_deploy'))->toBeTrue()
        ->and($response->json('data.1.id'))->toBe('201')
        ->and($response->json('data.1.attributes.name'))->toBe('staging.example.com');
});

test('sites show returns single site', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('site-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->sites()->organizationsSitesShow('1', 200);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('200')
        ->and($response->json('data.type'))->toBe('sites')
        ->and($response->json('data.attributes.name'))->toBe('example.com')
        ->and($response->json('data.attributes.directory'))->toBe('/public')
        ->and($response->json('data.attributes.status'))->toBe('installed')
        ->and($response->json('data.attributes.repository'))->toBe('user/repo')
        ->and($response->json('data.attributes.repository_provider'))->toBe('github')
        ->and($response->json('data.attributes.repository_branch'))->toBe('main')
        ->and($response->json('data.attributes.quick_deploy'))->toBeTrue()
        ->and($response->json('data.attributes.is_secured'))->toBeTrue()
        ->and($response->json('data.attributes.php_version'))->toBe('php83');
});

test('sites show returns 404 for non-existent site', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Site'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->sites()->organizationsSitesShow('1', 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('sites index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->sites()->organizationsServersSitesIndex(
        organization: '1',
        server: 100,
        sort: null,
        include: null,
        pagesize: null,
        pagecursor: null,
        filtername: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});

test('sites destroy deletes a site', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::make([], 204));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->sites()->organizationsServersSitesDestroy('1', 100, 200);

    expect($response->status())->toBe(204);
});
