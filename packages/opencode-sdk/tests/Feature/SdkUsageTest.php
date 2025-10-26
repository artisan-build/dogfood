<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionCreate;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionList;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('connector can be resolved from Laravel container', function (): void {
    $connector = app(OpenCode::class);

    expect($connector)->toBeInstanceOf(OpenCode::class);
});

test('can list sessions with mocked response', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            ['id' => 'ses_123', 'title' => 'Test Session 1'],
            ['id' => 'ses_456', 'title' => 'Test Session 2'],
        ], 200),
    ]);

    $connector = new OpenCode;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SessionList);

    expect($response->status())->toBe(200)
        ->and($response->json())->toBeArray()
        ->and($response->json())->toHaveCount(2)
        ->and($response->json()[0]['id'])->toBe('ses_123');
});

test('can create session with mocked response', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'id' => 'ses_new',
            'title' => 'New Session',
            'model' => 'claude-3-5-sonnet-20241022',
        ], 201),
    ]);

    $connector = new OpenCode;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SessionCreate);

    expect($response->status())->toBe(201)
        ->and($response->json()['id'])->toBe('ses_new')
        ->and($response->json()['title'])->toBe('New Session');
});

test('connector maintains singleton instance from container', function (): void {
    $connector1 = app(OpenCode::class);
    $connector2 = app(OpenCode::class);

    expect($connector1)->toBe($connector2);
});
