<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Console\Commands\CreateDatabaseCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateDatabaseUserCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroyDatabaseCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroyDatabaseUserCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetDatabaseCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetDatabaseUserCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListDatabasesCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListDatabaseUsersCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\UpdateDatabaseUserCommand;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-sdk.api_token', 'test-token');
    config()->set('forge-sdk.logging.channel', 'null');
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListDatabasesCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetDatabaseCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'database' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateDatabaseCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyDatabaseCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'database' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyDatabaseCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'database' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListDatabaseUsersCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetDatabaseUserCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'database-user' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateDatabaseUserCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(UpdateDatabaseUserCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'database-user' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyDatabaseUserCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'database-user' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyDatabaseUserCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'database-user' => 1,
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Database user destroyed successfully');
});
