<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\GetOrganizationCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListOrganizationsCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-client.api_token', 'test-token');
    config()->set('forge-client.logging.channel', 'null');
});

test('list organizations command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Test Organization',
                    'slug' => 'test-org',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
                [
                    'id' => 2,
                    'name' => 'Another Organization',
                    'slug' => 'another-org',
                    'created_at' => '2024-01-02T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListOrganizationsCommand::class)
        ->assertExitCode(0)
        ->expectsOutputToContain('Test Organization')
        ->expectsOutputToContain('Another Organization');
});

test('list organizations command handles errors gracefully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['error' => 'Unauthorized'], 401),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListOrganizationsCommand::class)
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to list organizations');
});

test('get organization command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'Test Organization',
                'slug' => 'test-org',
                'created_at' => '2024-01-01T00:00:00Z',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetOrganizationCommand::class, ['organization' => 'test-org'])
        ->assertExitCode(0)
        ->expectsOutputToContain('Test Organization')
        ->expectsOutputToContain('test-org');
});

test('get organization command handles not found', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['error' => 'Organization not found'], 404),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetOrganizationCommand::class, ['organization' => 'non-existent'])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to get organization');
});

test('list organizations command accepts pagination options', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Test Organization',
                    'slug' => 'test-org',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListOrganizationsCommand::class, [
        '--pagesize' => 10,
        '--pagecursor' => 'abc123',
    ])
        ->assertExitCode(0);

    $mockClient->assertSentCount(1);
});
