<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeClient;
    $this->mockClient = new MockClient;
});

test('recipes index returns list of recipes', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('recipes-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->recipes()->organizationsRecipesIndex(
        organization: '1',
        pagesize: null,
        pagecursor: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.0.id'))->toBe('1000')
        ->and($response->json('data.0.type'))->toBe('recipes')
        ->and($response->json('data.0.attributes.name'))->toBe('Install Node.js')
        ->and($response->json('data.0.attributes.user'))->toBe('root');
});

test('recipes show returns single recipe', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('recipe-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->recipes()->organizationsRecipesShow('1', 1000);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data.id'))->toBe('1000')
        ->and($response->json('data.attributes.name'))->toBe('Install Node.js');
});

test('recipes show returns 404 for non-existent recipe', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Recipe'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->recipes()->organizationsRecipesShow('1', 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('recipes index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->recipes()->organizationsRecipesIndex(
        organization: '1',
        pagesize: null,
        pagecursor: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
