<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\ListServerCredentialsCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-client.api_token', 'test-token');
    config()->set('forge-client.logging.channel', 'null');
});

test('list server credentials command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'type' => 'serverCredentials',
                    'attributes' => [
                        'name' => 'My DigitalOcean Account',
                        'provider' => 'ocean2',
                    ],
                ],
                [
                    'id' => 2,
                    'type' => 'serverCredentials',
                    'attributes' => [
                        'name' => 'My Hetzner Account',
                        'provider' => 'hetzner',
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListServerCredentialsCommand::class, ['organization' => 'test-org'])
        ->assertExitCode(0)
        ->expectsOutputToContain('My DigitalOcean Account')
        ->expectsOutputToContain('My Hetzner Account');
});

test('list server credentials command handles errors gracefully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make(['error' => 'Unauthorized'], 401),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListServerCredentialsCommand::class, ['organization' => 'test-org'])
        ->assertExitCode(1)
        ->expectsOutputToContain('Failed to list server credentials');
});

test('list server credentials command accepts pagination options', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'type' => 'serverCredentials',
                    'attributes' => [
                        'name' => 'My AWS Account',
                        'provider' => 'aws',
                    ],
                ],
            ],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListServerCredentialsCommand::class, [
        'organization' => 'test-org',
        '--pagesize' => 10,
        '--pagecursor' => 'abc123',
    ])
        ->assertExitCode(0);

    $mockClient->assertSentCount(1);
});

test('list server credentials command uses environment organization', function (): void {
    config()->set('forge-client.default_organization', 'env-org');

    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [],
        ], 200),
    ]);

    $sdk = resolve(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListServerCredentialsCommand::class)
        ->assertExitCode(0);

    $mockClient->assertSentCount(1);
});

test('list server credentials command requires organization', function (): void {
    config()->set('forge-client.default_organization', null);

    $this->artisan(ListServerCredentialsCommand::class)
        ->assertExitCode(1)
        ->expectsOutputToContain('Organization is required');
});
