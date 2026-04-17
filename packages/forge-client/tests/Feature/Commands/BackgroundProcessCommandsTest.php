<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\CreateBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListBackgroundProcessesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\RestartBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\UpdateBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-client.api_token', 'test-token');
    config()->set('forge-client.logging.channel', 'null');
});

test('list background processes command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'user' => 'forge',
                    'command' => 'php artisan queue:work',
                    'directory' => '/home/forge/site.com',
                    'status' => 'running',
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListBackgroundProcessesCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('queue:work')
        ->expectsOutputToContain('Listed 1 background process(es)');
});

test('get background process command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'user' => 'forge',
                'command' => 'php artisan queue:work',
                'directory' => '/home/forge/site.com',
                'status' => 'running',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetBackgroundProcessCommand::class, [
        'background-process' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('queue:work')
        ->expectsOutputToContain('running');
});

test('create background process command executes successfully with confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'command' => 'php artisan queue:work',
                'status' => 'installing',
            ],
        ], 201),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateBackgroundProcessCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Background process created successfully');
});

test('update background process command executes successfully with confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'command' => 'php artisan queue:work --queue=high',
                'status' => 'running',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(UpdateBackgroundProcessCommand::class, [
        'background-process' => 1,
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Background process updated successfully');
});

test('restart background process command requires confirmation', function (): void {
    $mockClient = new MockClient([
        // First call to get process details for confirmation
        MockResponse::make([
            'data' => [
                'id' => 1,
                'command' => 'php artisan queue:work',
                'status' => 'running',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(RestartBackgroundProcessCommand::class, [
        'background-process' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->expectsConfirmation("Are you sure you want to restart 'php artisan queue:work'?", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');
});

test('destroy background process command requires confirmation', function (): void {
    $mockClient = new MockClient([
        // First call to get process details for confirmation
        MockResponse::make([
            'data' => [
                'id' => 1,
                'command' => 'php artisan queue:work',
                'status' => 'running',
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyBackgroundProcessCommand::class, [
        'background-process' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy background process 'php artisan queue:work'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');
});

test('destroy background process command executes with confirmation skip', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'command' => 'php artisan queue:work',
                'status' => 'running',
            ],
        ], 200),
        MockResponse::make([], 204),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyBackgroundProcessCommand::class, [
        'background-process' => 1,
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Background process destroyed successfully');
});
