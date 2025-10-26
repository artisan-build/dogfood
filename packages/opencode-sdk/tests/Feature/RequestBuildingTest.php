<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ConfigGet;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FileList;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionDelete;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionGet;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionMessages;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionPrompt;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('can build session get request', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['id' => 'ses_123', 'title' => 'Test'], 200),
    ]);

    $connector = new OpenCode;
    $connector->withMockClient($mockClient);

    $request = new SessionGet(id: 'ses_123');
    $response = $connector->send($request);

    expect($response->status())->toBe(200);
});

test('can build session delete request', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([], 204),
    ]);

    $connector = new OpenCode;
    $connector->withMockClient($mockClient);

    $request = new SessionDelete(id: 'ses_123');
    $response = $connector->send($request);

    expect($response->status())->toBe(204);
});

test('can build session prompt request', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['message' => 'Response'], 200),
    ]);

    $connector = new OpenCode;
    $connector->withMockClient($mockClient);

    $request = new SessionPrompt(id: 'ses_123');
    $response = $connector->send($request);

    expect($response->status())->toBe(200);
});

test('can build session messages request', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            ['id' => 'msg_1', 'role' => 'user', 'content' => 'Hello'],
            ['id' => 'msg_2', 'role' => 'assistant', 'content' => 'Hi'],
        ], 200),
    ]);

    $connector = new OpenCode;
    $connector->withMockClient($mockClient);

    $request = new SessionMessages(id: 'ses_123');
    $response = $connector->send($request);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveCount(2);
});

test('can build config get request', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['setting' => 'value'], 200),
    ]);

    $connector = new OpenCode;
    $connector->withMockClient($mockClient);

    $request = new ConfigGet;
    $response = $connector->send($request);

    expect($response->status())->toBe(200);
});

test('can build file list request with path', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            ['name' => 'file1.php', 'path' => '/src/file1.php'],
            ['name' => 'file2.php', 'path' => '/src/file2.php'],
        ], 200),
    ]);

    $connector = new OpenCode;
    $connector->withMockClient($mockClient);

    $request = new FileList(directory: null, path: '/src');
    $response = $connector->send($request);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveCount(2);
});
