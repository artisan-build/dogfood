<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Console\Commands\CreateBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroyBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListBackgroundProcessesCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\RestartBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\UpdateBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-sdk.api_token', 'test-token');
    config()->set('forge-sdk.logging.channel', 'null');
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListBackgroundProcessesCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetBackgroundProcessCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'background-process' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateBackgroundProcessCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(UpdateBackgroundProcessCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'background-process' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(RestartBackgroundProcessCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'background-process' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyBackgroundProcessCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'background-process' => 1,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyBackgroundProcessCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'background-process' => 1,
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Background process destroyed successfully');
});
