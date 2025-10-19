<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Console\Commands\GetServerCommand;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    config(['forge-sdk.api_token' => 'test-token']);
    config(['forge-sdk.base_url' => 'https://forge.laravel.com/api/v1']);
});

test('get server command resolves server by name', function () {
    $mockClient = new MockClient([
        // First request: list servers with name filter to resolve server name to ID
        MockResponse::make([
            'data' => [
                [
                    'id' => 456,
                    'name' => 'my-staging-server',
                    'provider' => 'ocean',
                    'region' => 'nyc3',
                    'size' => '1gb',
                    'ip_address' => '192.168.1.1',
                    'php_version' => 'php84',
                    'ubuntu_version' => '24.04',
                    'status' => 'installed',
                ],
            ],
        ], 200),

        // Second request: get the actual server details
        MockResponse::make([
            'data' => [
                'id' => 456,
                'name' => 'my-staging-server',
                'provider' => 'ocean',
                'region' => 'nyc3',
                'size' => '1gb',
                'ip_address' => '192.168.1.1',
                'php_version' => 'php84',
                'ubuntu_version' => '24.04',
                'status' => 'installed',
                'created_at' => '2025-01-01T00:00:00Z',
            ],
        ], 200),
    ]);

    app(ForgeSdk::class)->withMockClient($mockClient);

    $this->artisan(GetServerCommand::class, [
        'organization' => 'my-org',
        'server' => 'my-staging-server',
    ])
        ->expectsOutput('Server: my-staging-server')
        ->assertExitCode(0);
});

test('get server command resolves server by ID', function () {
    $mockClient = new MockClient([
        // First request: get the server details directly (no name resolution needed)
        MockResponse::make([
            'data' => [
                'id' => 456,
                'name' => 'my-staging-server',
                'provider' => 'ocean',
                'region' => 'nyc3',
                'size' => '1gb',
                'ip_address' => '192.168.1.1',
                'php_version' => 'php84',
                'ubuntu_version' => '24.04',
                'status' => 'installed',
                'created_at' => '2025-01-01T00:00:00Z',
            ],
        ], 200),
    ]);

    app(ForgeSdk::class)->withMockClient($mockClient);

    $this->artisan(GetServerCommand::class, [
        'organization' => 'my-org',
        'server' => 456,
    ])
        ->expectsOutput('Server: my-staging-server')
        ->assertExitCode(0);
});

test('get server command fails when server name not found', function () {
    $mockClient = new MockClient([
        // First request: list servers with name filter returns empty
        MockResponse::make([
            'data' => [],
        ], 200),
    ]);

    app(ForgeSdk::class)->withMockClient($mockClient);

    $this->artisan(GetServerCommand::class, [
        'organization' => 'my-org',
        'server' => 'non-existent-server',
    ])
        ->expectsOutputToContain("Server 'non-existent-server' not found")
        ->assertExitCode(1);
});

test('get server command fails when multiple servers have same name', function () {
    $mockClient = new MockClient([
        // First request: list servers with name filter returns multiple
        MockResponse::make([
            'data' => [
                [
                    'id' => 456,
                    'name' => 'staging',
                    'provider' => 'ocean',
                    'region' => 'nyc3',
                    'size' => '1gb',
                    'ip_address' => '192.168.1.1',
                    'php_version' => 'php84',
                    'ubuntu_version' => '24.04',
                    'status' => 'installed',
                ],
                [
                    'id' => 789,
                    'name' => 'staging',
                    'provider' => 'aws',
                    'region' => 'us-east-1',
                    'size' => 't2.small',
                    'ip_address' => '10.0.0.1',
                    'php_version' => 'php83',
                    'ubuntu_version' => '22.04',
                    'status' => 'installed',
                ],
            ],
        ], 200),
    ]);

    app(ForgeSdk::class)->withMockClient($mockClient);

    $this->artisan(GetServerCommand::class, [
        'organization' => 'my-org',
        'server' => 'staging',
    ])
        ->expectsOutputToContain('Multiple servers found')
        ->assertExitCode(1);
});

test('get server command resolves organization by ID', function () {
    $mockClient = new MockClient([
        // First request: resolve organization ID to slug
        MockResponse::make([
            'data' => [
                [
                    'id' => 123,
                    'slug' => 'my-org',
                    'name' => 'My Organization',
                ],
                [
                    'id' => 456,
                    'slug' => 'other-org',
                    'name' => 'Other Organization',
                ],
            ],
        ], 200),

        // Second request: get the server details
        MockResponse::make([
            'data' => [
                'id' => 789,
                'name' => 'my-server',
                'provider' => 'ocean',
                'region' => 'nyc3',
                'size' => '1gb',
                'ip_address' => '192.168.1.1',
                'php_version' => 'php84',
                'ubuntu_version' => '24.04',
                'status' => 'installed',
                'created_at' => '2025-01-01T00:00:00Z',
            ],
        ], 200),
    ]);

    app(ForgeSdk::class)->withMockClient($mockClient);

    $this->artisan(GetServerCommand::class, [
        'organization' => 123,
        'server' => 789,
    ])
        ->expectsOutput('Server: my-server')
        ->assertExitCode(0);
});
