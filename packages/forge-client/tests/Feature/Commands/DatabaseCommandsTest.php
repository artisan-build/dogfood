<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\CreateDatabaseCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateDatabaseUserCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyDatabaseCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyDatabaseUserCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetDatabaseCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetDatabaseUserCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListDatabasesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListDatabaseUsersCommand;
use ArtisanBuild\ForgeClient\Console\Commands\UpdateDatabaseUserCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-client.api_token', 'test-token');
    config()->set('forge-client.logging.channel', 'null');
});

// Database Schema Tests

test('list databases command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'production_db',
                    'status' => 'installed',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
                [
                    'id' => 2,
                    'name' => 'staging_db',
                    'status' => 'installed',
                    'created_at' => '2024-01-02T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListDatabasesCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('production_db')
        ->expectsOutputToContain('staging_db')
        ->expectsOutputToContain('Listed 2 database(s)');
});

test('get database command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'production_db',
                'status' => 'installed',
                'created_at' => '2024-01-01T00:00:00Z',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetDatabaseCommand::class, [
        'database' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('production_db')
        ->expectsOutputToContain('installed');
});

test('create database command executes successfully with confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'new_database',
                'status' => 'installing',
            ],
        ], 201),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateDatabaseCommand::class, [
        'name' => 'test_database',
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Database created successfully');
});

test('destroy database command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'test_database',
                'status' => 'installed',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyDatabaseCommand::class, [
        'database' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy database 'test_database'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');
});

test('destroy database command executes with confirmation skip', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'test_database',
                'status' => 'installed',
            ],
        ], 200),
        MockResponse::make([], 204),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyDatabaseCommand::class, [
        'database' => 1,
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Database destroyed successfully');
});

// Database User Tests

test('list database users command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'forge_user',
                    'status' => 'installed',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListDatabaseUsersCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('forge_user')
        ->expectsOutputToContain('Listed 1 database user(s)');
});

test('get database user command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'forge_user',
                'status' => 'installed',
                'created_at' => '2024-01-01T00:00:00Z',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetDatabaseUserCommand::class, [
        'database-user' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('forge_user')
        ->expectsOutputToContain('installed');
});

test('create database user command executes successfully with confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'new_user',
                'status' => 'installing',
            ],
        ], 201),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateDatabaseUserCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Database user created successfully');
});

test('update database user command executes successfully with confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'forge_user',
                'status' => 'installed',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(UpdateDatabaseUserCommand::class, [
        'database-user' => 1,
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Database user updated successfully');
});

test('destroy database user command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'test_user',
                'status' => 'installed',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyDatabaseUserCommand::class, [
        'database-user' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy database user 'test_user'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');
});

test('destroy database user command executes with confirmation skip', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'test_user',
                'status' => 'installed',
            ],
        ], 200),
        MockResponse::make([], 204),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyDatabaseUserCommand::class, [
        'database-user' => 1,
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Database user destroyed successfully');
});
