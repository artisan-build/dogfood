<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\ListProvidersCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListProviderSizesCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-client.api_token', 'test-token');
    config()->set('forge-client.logging.channel', 'null');
});

test('list providers command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'DigitalOcean',
                    'type' => 'ocean2',
                ],
                [
                    'id' => 2,
                    'name' => 'Hetzner',
                    'type' => 'hetzner',
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListProvidersCommand::class)
        ->assertExitCode(0)
        ->expectsOutputToContain('DigitalOcean')
        ->expectsOutputToContain('Hetzner');
});

test('list providers command handles errors gracefully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['error' => 'Unauthorized'], 401),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListProvidersCommand::class)
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to list providers');
});

test('list providers command accepts pagination options', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'DigitalOcean',
                    'type' => 'ocean2',
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListProvidersCommand::class, [
        '--pagesize' => 10,
        '--pagecursor' => 'abc123',
    ])
        ->assertExitCode(0);

    $mockClient->assertSentCount(1);
});

test('list provider sizes command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'type' => 'providerSizes',
                    'attributes' => [
                        'name' => '1 GB RAM · 1 vCPU Core · 25 GB SSD',
                        'code' => 'small-1gb',
                        'series' => 'standard',
                        'category' => 'general',
                        'cpus' => 1,
                        'disk_type' => 'ssd',
                        'architecture' => 'x64',
                        'ram' => 1024,
                        'disk' => 25600, // MB
                    ],
                ],
                [
                    'id' => 2,
                    'type' => 'providerSizes',
                    'attributes' => [
                        'name' => '2 GB RAM · 1 vCPU Core · 50 GB SSD',
                        'code' => 'small-2gb',
                        'series' => 'standard',
                        'category' => 'general',
                        'cpus' => 1,
                        'disk_type' => 'ssd',
                        'architecture' => 'x64',
                        'ram' => 2048,
                        'disk' => 51200, // MB
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListProviderSizesCommand::class, ['provider' => 1])
        ->assertExitCode(0)
        ->expectsOutputToContain('1 GB RAM')
        ->expectsOutputToContain('2 GB RAM');
});

test('list provider sizes command handles errors gracefully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['error' => 'Unauthorized'], 401),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListProviderSizesCommand::class, ['provider' => 1])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to list provider sizes');
});

test('list provider sizes command accepts pagination options', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'type' => 'providerSizes',
                    'attributes' => [
                        'name' => '1 GB RAM · 1 vCPU Core · 25 GB SSD',
                        'code' => 'small-1gb',
                        'series' => 'standard',
                        'category' => 'general',
                        'cpus' => 1,
                        'disk_type' => 'ssd',
                        'architecture' => 'x64',
                        'ram' => 1024,
                        'disk' => 25600,
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListProviderSizesCommand::class, [
        'provider' => 1,
        '--pagesize' => 10,
        '--pagecursor' => 'abc123',
    ])
        ->assertExitCode(0);

    $mockClient->assertSentCount(1);
});

test('list provider sizes command handles provider not found', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['error' => 'Provider not found'], 404),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListProviderSizesCommand::class, ['provider' => 999])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to list provider sizes');
});
