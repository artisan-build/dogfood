<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\CreateServerCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyServerCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetServerCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListServersCommand;
use ArtisanBuild\ForgeClient\Console\Commands\RebootServerCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-client.api_token', 'test-token');
    config()->set('forge-client.logging.channel', 'null');
});

test('list servers command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'attributes' => [
                        'name' => 'production-web-1',
                        'provider' => 'digitalocean',
                        'region' => 'nyc3',
                        'php_version' => '8.3',
                        'ip_address' => '192.168.1.1',
                        'connection_status' => 'active',
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListServersCommand::class, ['organization' => 'test-org'])
        ->assertExitCode(0)
        ->expectsOutputToContain('production-web-1')
        ->expectsOutputToContain('Listed 1 server(s)');
});

test('list servers command handles filters', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'attributes' => [
                        'name' => 'nyc-server',
                        'provider' => 'digitalocean',
                        'region' => 'nyc3',
                        'php_version' => '8.3',
                        'ip_address' => '192.168.1.1',
                        'connection_status' => 'active',
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListServersCommand::class, [
        'organization' => 'test-org',
        '--filter-region' => 'nyc3',
        '--filter-provider' => 'digitalocean',
    ])
        ->assertExitCode(0);

    $mockClient->assertSentCount(1);
});

test('get server command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 123,
                'attributes' => [
                    'name' => 'production-web-1',
                    'provider' => 'digitalocean',
                    'region' => 'nyc3',
                    'size' => 's-1vcpu-1gb',
                    'ip_address' => '192.168.1.1',
                    'php_version' => '8.3',
                    'ubuntu_version' => '22.04',
                    'status' => 'active',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetServerCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('production-web-1')
        ->expectsOutputToContain('192.168.1.1')
        ->expectsOutputToContain('8.3');
});

test('create server command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 456,
                'name' => 'new-server',
                'status' => 'provisioning',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateServerCommand::class, [
        'organization' => 'test-org',
        '--name' => 'new-server',
        '--provider' => 'ocean2',
        '--credential' => '1',
        '--region' => 'nyc3',
        '--size' => '1',
        '--database' => 'mysql8',
    ])
        ->expectsConfirmation('Are you sure you want to create this server?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Server created successfully');
});

test('create server command can skip confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 456,
                'name' => 'new-server',
                'status' => 'provisioning',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateServerCommand::class, [
        'organization' => 'test-org',
        '--name' => 'new-server',
        '--provider' => 'ocean2',
        '--credential' => '1',
        '--region' => 'nyc3',
        '--size' => '1',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Server created successfully');

    $mockClient->assertSentCount(1);
});

test('create server command validates required options', function (): void {
    $this->artisan(CreateServerCommand::class, [
        'organization' => 'test-org',
        '--name' => 'new-server',
        // Missing --provider, --credential, --size
    ])
        ->assertExitCode(1)
        ->expectsOutputToContain('Missing required option');
});

test('create server command requires region for all providers', function (): void {
    $this->artisan(CreateServerCommand::class, [
        'organization' => 'test-org',
        '--name' => 'ocean-server',
        '--provider' => 'ocean2',
        '--credential' => '1',
        '--size' => '1',
        '--dangerously-skip-confirmation' => true,
        // Missing --region
    ])
        ->assertExitCode(1)
        ->expectsOutputToContain('Missing required option: --region');
});

test('create server command uses config defaults for php and database', function (): void {
    config()->set('forge-client.default_php_version', 'php83');
    config()->set('forge-client.default_database', 'postgres');

    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 999,
                'name' => 'config-defaults-server',
                'status' => 'provisioning',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateServerCommand::class, [
        'organization' => 'test-org',
        '--name' => 'config-defaults-server',
        '--provider' => 'laravel',
        '--credential' => '1',
        '--region' => 'us-east',
        '--size' => '1',
        '--dangerously-skip-confirmation' => true,
        // Not passing --php-version or --database
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Server created successfully');
});

test('destroy server command requires confirmation', function (): void {
    $mockClient = new MockClient([
        // First request to get server details
        MockResponse::make([
            'data' => [
                'id' => 123,
                'attributes' => [
                    'name' => 'server-to-delete',
                    'status' => 'active',
                ],
            ],
        ], 200),
        // Second request to destroy
        MockResponse::make([], 204),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyServerCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy server 'server-to-delete'", 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('destroyed successfully');
});

test('destroy server command can skip confirmation', function (): void {
    $mockClient = new MockClient([
        // Server details
        MockResponse::make([
            'data' => [
                'id' => 123,
                'attributes' => [
                    'name' => 'server-to-delete',
                    'status' => 'active',
                ],
            ],
        ], 200),
        // Destroy
        MockResponse::make([], 204),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyServerCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('destroyed successfully');

    $mockClient->assertSentCount(2);
});

test('destroy server command can be cancelled', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 123,
                'attributes' => [
                    'name' => 'server-to-keep',
                    'status' => 'active',
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyServerCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy server 'server-to-keep'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');

    $mockClient->assertSentCount(1); // Only the GET request, no destroy
});

test('reboot server command requires confirmation', function (): void {
    $mockClient = new MockClient([
        // Server details
        MockResponse::make([
            'data' => [
                'id' => 123,
                'name' => 'server-to-reboot',
                'status' => 'active',
            ],
        ], 200),
        // Reboot action
        MockResponse::make([], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(RebootServerCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
    ])
        ->expectsConfirmation("Are you sure you want to reboot 'server-to-reboot'?", 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('is rebooting');
});

test('reboot server command can skip confirmation', function (): void {
    $mockClient = new MockClient([
        // Server details
        MockResponse::make([
            'data' => [
                'id' => 123,
                'name' => 'server-to-reboot',
                'status' => 'active',
            ],
        ], 200),
        // Reboot action
        MockResponse::make([], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(RebootServerCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('is rebooting');

    $mockClient->assertSentCount(2);
});

test('list servers command uses default organization from config', function (): void {
    config()->set('forge-client.default_organization', 'default-org');

    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'attributes' => [
                        'name' => 'production-web-1',
                        'provider' => 'digitalocean',
                        'region' => 'nyc3',
                        'php_version' => '8.3',
                        'ip_address' => '192.168.1.1',
                        'connection_status' => 'active',
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListServersCommand::class)
        ->assertExitCode(0)
        ->expectsOutputToContain('production-web-1');
});

test('list servers command requires organization when not in config', function (): void {
    config()->set('forge-client.default_organization', null);

    $this->artisan(ListServersCommand::class)
        ->assertExitCode(1)
        ->expectsOutputToContain('Organization is required');
});

test('get server command uses default organization and server from config', function (): void {
    config()->set('forge-client.default_organization', 'default-org');
    config()->set('forge-client.default_server', '123');

    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 123,
                'attributes' => [
                    'name' => 'production-web-1',
                    'provider' => 'digitalocean',
                    'region' => 'nyc3',
                    'size' => 's-1vcpu-1gb',
                    'ip_address' => '192.168.1.1',
                    'php_version' => '8.3',
                    'ubuntu_version' => '22.04',
                    'status' => 'active',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetServerCommand::class)
        ->assertExitCode(0)
        ->expectsOutputToContain('production-web-1');
});

test('get server command argument overrides config default', function (): void {
    config()->set('forge-client.default_organization', 'default-org');
    config()->set('forge-client.default_server', '999');

    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 123,
                'attributes' => [
                    'name' => 'specific-server',
                    'provider' => 'digitalocean',
                    'region' => 'nyc3',
                    'size' => 's-1vcpu-1gb',
                    'ip_address' => '192.168.1.1',
                    'php_version' => '8.3',
                    'ubuntu_version' => '22.04',
                    'status' => 'active',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetServerCommand::class, [
        'server' => 123,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('specific-server');
});
