<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeSdk;
    $this->mockClient = new MockClient;
});

test('organizations index returns list of organizations', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('organizations-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->organizations()->organizationsIndex(
        pagesize: null,
        pagecursor: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(2)
        ->and($response->json('data.0.id'))->toBe('1')
        ->and($response->json('data.0.type'))->toBe('organizations')
        ->and($response->json('data.0.attributes.name'))->toBe('Acme Corporation')
        ->and($response->json('data.0.attributes.slug'))->toBe('acme-corp');
});

test('organizations show returns single organization', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('organization-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->organizations()->organizationsShow('1');

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('1')
        ->and($response->json('data.type'))->toBe('organizations')
        ->and($response->json('data.attributes.name'))->toBe('Acme Corporation')
        ->and($response->json('data.attributes.slug'))->toBe('acme-corp');
});

test('organizations show returns 404 for non-existent organization', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Organization'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->organizations()->organizationsShow('999');

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('organizations index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->organizations()->organizationsIndex(
        pagesize: null,
        pagecursor: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
