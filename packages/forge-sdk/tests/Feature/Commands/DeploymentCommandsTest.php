<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Console\Commands\GetDeploymentCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListDeploymentsCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\TriggerDeploymentCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\UpdateDeploymentScriptCommand;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-sdk.api_token', 'test-token');
    config()->set('forge-sdk.logging.channel', 'null');
});

test('list deployments command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 300,
                    'status' => 'finished',
                    'commit_hash' => 'abc123def456',
                    'commit_author' => 'John Doe',
                    'commit_message' => 'Update dependencies',
                    'started_at' => '2025-01-15T14:00:00Z',
                    'ended_at' => '2025-01-15T14:02:30Z',
                    'duration' => 150,
                ],
                [
                    'id' => 299,
                    'status' => 'finished',
                    'commit_hash' => 'def456ghi789',
                    'commit_author' => 'Jane Smith',
                    'commit_message' => 'Fix bug in authentication',
                    'started_at' => '2025-01-14T10:00:00Z',
                    'ended_at' => '2025-01-14T10:01:45Z',
                    'duration' => 105,
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListDeploymentsCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Update dependencies')
        ->expectsOutputToContain('Fix bug in authentication')
        ->expectsOutputToContain('Listed 2 deployment(s)');
});

test('get deployment command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 300,
                'status' => 'finished',
                'commit_hash' => 'abc123def456',
                'commit_author' => 'John Doe',
                'commit_message' => 'Update dependencies',
                'started_at' => '2025-01-15T14:00:00Z',
                'ended_at' => '2025-01-15T14:02:30Z',
                'duration' => 150,
                'created_at' => '2025-01-15T14:00:00Z',
                'updated_at' => '2025-01-15T14:02:30Z',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetDeploymentCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
        'deployment' => '300',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Update dependencies')
        ->expectsOutputToContain('finished')
        ->expectsOutputToContain('abc123def456');
});

test('trigger deployment command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 301,
                'status' => 'running',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(TriggerDeploymentCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
    ])
        ->expectsConfirmation('Are you sure you want to trigger a deployment?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Deployment triggered successfully');
});

test('trigger deployment command can skip confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 301,
                'status' => 'running',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(TriggerDeploymentCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Deployment triggered successfully');

    $mockClient->assertSentCount(1);
});

test('update deployment script command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 200,
                'deployment_script' => 'cd /home/forge/site && php artisan migrate --force',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(UpdateDeploymentScriptCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
        '--script' => 'cd /home/forge/site && php artisan migrate --force',
    ])
        ->expectsConfirmation('Are you sure you want to update the deployment script?', 'yes')
        ->assertExitCode(0)
        ->expectsOutputToContain('Deployment script updated successfully');
});

test('update deployment script command can skip confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 200,
                'deployment_script' => 'cd /home/forge/site && php artisan migrate --force',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(UpdateDeploymentScriptCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
        '--script' => 'cd /home/forge/site && php artisan migrate --force',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Deployment script updated successfully');

    $mockClient->assertSentCount(1);
});

test('list deployments command handles API errors gracefully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'errors' => [
                ['title' => 'Unauthorized', 'detail' => 'Invalid API token'],
            ],
        ], 401),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListDeploymentsCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
    ])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to list deployments');
});

test('get deployment command handles 404 errors', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'errors' => [
                ['title' => 'Not Found', 'detail' => 'Deployment not found'],
            ],
        ], 404),
    ]);

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetDeploymentCommand::class, [
        'organization' => 'test-org',
        'server' => '100',
        'site' => '200',
        'deployment' => '999',
    ])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to get deployment');
});
