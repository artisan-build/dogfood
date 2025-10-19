<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Console\Commands\CreateSiteCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DeploySiteCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroySiteCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DisableQuickDeployCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\EnableQuickDeployCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetSiteCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListSitesCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\UpdateSiteCommand;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-sdk.api_token', 'test-token');
    config()->set('forge-sdk.logging.channel', 'null');
});

test('list sites command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => '200',
                    'type' => 'sites',
                    'attributes' => [
                        'id' => 200,
                        'name' => 'example.com',
                        'directory' => '/public',
                        'status' => 'installed',
                        'repository' => 'user/repo',
                        'repository_branch' => 'main',
                        'quick_deploy' => true,
                        'php_version' => 'php83',
                    ],
                ],
                [
                    'id' => '201',
                    'type' => 'sites',
                    'attributes' => [
                        'id' => 201,
                        'name' => 'staging.example.com',
                        'directory' => '/public',
                        'status' => 'installed',
                        'repository' => 'user/repo',
                        'repository_branch' => 'staging',
                        'quick_deploy' => false,
                        'php_version' => 'php83',
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListSitesCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('example.com')
        ->expectsOutputToContain('Listed 2 site(s)');
});

test('list sites command handles filters', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 200,
                    'name' => 'example.com',
                    'directory' => '/public',
                    'status' => 'installed',
                    'repository' => 'user/repo',
                    'repository_branch' => 'main',
                    'quick_deploy' => true,
                    'php_version' => 'php83',
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListSitesCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        '--filter-name' => 'example.com',
    ])
        ->assertExitCode(0);

    $mockClient->assertSentCount(1);
});

test('get site command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 200,
                'name' => 'example.com',
                'directory' => '/public',
                'status' => 'installed',
                'repository' => 'user/repo',
                'repository_provider' => 'github',
                'repository_branch' => 'main',
                'repository_status' => 'installed',
                'quick_deploy' => true,
                'deployment_status' => 'finished',
                'project_type' => 'php',
                'php_version' => 'php83',
                'created_at' => '2025-01-10T12:00:00Z',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetSiteCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('example.com')
        ->expectsOutputToContain('installed')
        ->expectsOutputToContain('php83');
});

test('create site command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 202,
                'name' => 'new-site.com',
                'status' => 'installing',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateSiteCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        '--domain' => 'new-site.com',
        '--project-type' => 'php',
        '--directory' => '/public',
    ])
        ->expectsConfirmation('Are you sure you want to create this site?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Site created successfully');
});

test('create site command can skip confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 202,
                'name' => 'new-site.com',
                'status' => 'installing',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateSiteCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        '--domain' => 'new-site.com',
        '--project-type' => 'php',
        '--directory' => '/public',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Site created successfully');

    $mockClient->assertSentCount(1);
});

test('update site command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 200,
                'name' => 'example.com',
                'directory' => '/dist',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(UpdateSiteCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
        '--directory' => '/dist',
    ])
        ->expectsConfirmation('Are you sure you want to update this site?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Site updated successfully');
});

test('destroy site command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([], 204),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySiteCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
    ])
        ->expectsConfirmation('Are you sure you want to destroy this site?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Site destroyed successfully');
});

test('destroy site command can skip confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([], 204),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySiteCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Site destroyed successfully');

    $mockClient->assertSentCount(1);
});

test('deploy site command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 300,
                'status' => 'running',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DeploySiteCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
    ])
        ->expectsConfirmation('Are you sure you want to deploy this site?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Deployment triggered successfully');
});

test('enable quick deploy command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 200,
                'quick_deploy' => true,
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(EnableQuickDeployCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
    ])
        ->expectsConfirmation('Are you sure you want to enable quick deploy?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Quick deploy enabled successfully');
});

test('disable quick deploy command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 200,
                'quick_deploy' => false,
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DisableQuickDeployCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
    ])
        ->expectsConfirmation('Are you sure you want to disable quick deploy?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Quick deploy disabled successfully');
});

test('list sites command handles API errors gracefully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'errors' => [
                ['title' => 'Unauthorized', 'detail' => 'Invalid API token'],
            ],
        ], 401),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListSitesCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
    ])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to list sites');
});

test('get site command handles 404 errors', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'errors' => [
                ['title' => 'Not Found', 'detail' => 'Site not found'],
            ],
        ], 404),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetSiteCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '999',
    ])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to get site');
});
