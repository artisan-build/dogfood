<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\CreateSiteCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DeploySiteCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroySiteCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DisableQuickDeployCommand;
use ArtisanBuild\ForgeClient\Console\Commands\EnableQuickDeployCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetSiteCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListSitesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\UpdateSiteCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
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
                        'name' => 'example.com',
                        'url' => 'https://example.com',
                        'https' => true,
                        'aliases' => null,
                        'web_directory' => '/home/forge/example.com/public',
                        'deployment_status' => null,
                        'repository' => [
                            'url' => 'https://github.com/user/repo',
                            'branch' => 'main',
                        ],
                        'quick_deploy' => true,
                        'php_version' => 'PHP 8.3',
                    ],
                ],
                [
                    'id' => '201',
                    'type' => 'sites',
                    'attributes' => [
                        'name' => 'staging.example.com',
                        'url' => 'http://staging.example.com',
                        'https' => false,
                        'aliases' => null,
                        'web_directory' => '/home/forge/staging.example.com/public',
                        'deployment_status' => null,
                        'repository' => [
                            'url' => 'https://github.com/user/repo',
                            'branch' => 'staging',
                        ],
                        'quick_deploy' => false,
                        'php_version' => 'PHP 8.3',
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListSitesCommand::class, [
        'server' => '100',
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('example.com')
        ->expectsOutputToContain('Site #200')
        ->expectsOutputToContain('Listed 2 site(s)');
});

test('list sites command handles filters', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => '200',
                    'type' => 'sites',
                    'attributes' => [
                        'name' => 'example.com',
                        'url' => 'https://example.com',
                        'https' => true,
                        'aliases' => null,
                        'web_directory' => '/home/forge/example.com/public',
                        'deployment_status' => null,
                        'repository' => [
                            'url' => 'https://github.com/user/repo',
                            'branch' => 'main',
                        ],
                        'quick_deploy' => true,
                        'php_version' => 'PHP 8.3',
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListSitesCommand::class, [
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetSiteCommand::class, [
        'site' => '200',
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateSiteCommand::class, [
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateSiteCommand::class, [
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(UpdateSiteCommand::class, [
        'site' => '200',
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySiteCommand::class, [
        'site' => '200',
        'server' => '100',
        'organization' => 'test-org',
    ])
        ->expectsConfirmation('Are you sure you want to destroy this site?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Site destroyed successfully');
});

test('destroy site command can skip confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([], 204),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySiteCommand::class, [
        'site' => '200',
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DeploySiteCommand::class, [
        'site' => '200',
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(EnableQuickDeployCommand::class, [
        'site' => '200',
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DisableQuickDeployCommand::class, [
        'site' => '200',
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListSitesCommand::class, [
        'server' => '100',
        'organization' => 'test-org',
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

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetSiteCommand::class, [
        'site' => '999',
        'server' => '100',
        'organization' => 'test-org',
    ])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to get site');
});
