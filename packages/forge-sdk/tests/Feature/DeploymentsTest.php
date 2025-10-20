<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeSdk;
    $this->mockClient = new MockClient;
});

test('deployments index returns list of deployments for a site', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('deployments-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->deployments()->organizationsServersSitesDeploymentsIndex(
        organization: '1',
        server: 100,
        site: 200,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filtercommitHash: null,
        filtercommitMessage: null,
        filtercommitAuthor: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(2)
        ->and($response->json('data.0.id'))->toBe('300')
        ->and($response->json('data.0.type'))->toBe('deployments')
        ->and($response->json('data.0.attributes.status'))->toBe('finished')
        ->and($response->json('data.0.attributes.commit_hash'))->toBe('abc123def456')
        ->and($response->json('data.0.attributes.commit_author'))->toBe('John Doe')
        ->and($response->json('data.0.attributes.commit_message'))->toBe('Update dependencies')
        ->and($response->json('data.0.attributes.duration'))->toBe(150)
        ->and($response->json('data.1.id'))->toBe('299')
        ->and($response->json('data.1.attributes.commit_author'))->toBe('Jane Smith');
});

test('deployments show returns single deployment', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('deployment-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->deployments()->organizationsServersSitesDeploymentsShow('1', 100, 200, 300);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('300')
        ->and($response->json('data.type'))->toBe('deployments')
        ->and($response->json('data.attributes.status'))->toBe('finished')
        ->and($response->json('data.attributes.commit_hash'))->toBe('abc123def456')
        ->and($response->json('data.attributes.commit_author'))->toBe('John Doe')
        ->and($response->json('data.attributes.commit_message'))->toBe('Update dependencies')
        ->and($response->json('data.attributes.duration'))->toBe(150)
        ->and($response->json('data.attributes.started_at'))->toBe('2025-01-15T14:00:00Z')
        ->and($response->json('data.attributes.ended_at'))->toBe('2025-01-15T14:02:30Z');
});

test('deployments show returns 404 for non-existent deployment', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Deployment'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->deployments()->organizationsServersSitesDeploymentsShow('1', 100, 200, 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('deployments index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->deployments()->organizationsServersSitesDeploymentsIndex(
        organization: '1',
        server: 100,
        site: 200,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filtercommitHash: null,
        filtercommitMessage: null,
        filtercommitAuthor: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
