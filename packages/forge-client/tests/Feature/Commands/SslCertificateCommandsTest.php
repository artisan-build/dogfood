<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\ActivateSslCertificateCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateSslCertificateCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroySslCertificateCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetSslCertificateCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListSslCertificatesCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-client.api_token', 'test-token');
    config()->set('forge-client.logging.channel', 'null');
});

test('list ssl certificates command provides guidance', function (): void {
    $this->artisan(ListSslCertificatesCommand::class, [
        'site' => 456,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('SSL certificates in Laravel Forge are accessed per site domain')
        ->expectsOutputToContain('forge:list-domains')
        ->expectsOutputToContain('forge:get-ssl-certificate');
});

test('get ssl certificate command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'type' => 'letsencrypt',
                'status' => 'installed',
                'domain' => 'example.com',
                'active' => true,
                'existing' => false,
                'created_at' => '2024-01-01T00:00:00Z',
                'updated_at' => '2024-01-01T00:00:00Z',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetSslCertificateCommand::class, [
        'domain-record' => 789,
        'site' => 456,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('example.com')
        ->expectsOutputToContain('letsencrypt')
        ->expectsOutputToContain('installed');
});

test('create ssl certificate command executes successfully with confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'type' => 'letsencrypt',
                'status' => 'installing',
                'domain' => 'example.com',
                'active' => false,
            ],
        ], 201),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateSslCertificateCommand::class, [
        'domain-record' => 789,
        'site' => 456,
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('SSL certificate created successfully');
});

test('activate ssl certificate command provides guidance', function (): void {
    $this->artisan(ActivateSslCertificateCommand::class, [
        'site' => 456,
        'server' => 123,
        'organization' => 'test-org',
        'domain-record' => 789,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('SSL certificates in Laravel Forge are automatically activated')
        ->expectsOutputToContain('forge:create-ssl-certificate')
        ->expectsOutputToContain('There is no separate activation step');
});

test('destroy ssl certificate command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'type' => 'letsencrypt',
                'status' => 'installed',
                'domain' => 'example.com',
                'active' => true,
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySslCertificateCommand::class, [
        'domain-record' => 789,
        'site' => 456,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy the SSL certificate for 'example.com'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');
});

test('destroy ssl certificate command executes with confirmation skip', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'type' => 'letsencrypt',
                'status' => 'installed',
                'domain' => 'example.com',
                'active' => true,
            ],
        ], 200),
        MockResponse::make([], 204),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySslCertificateCommand::class, [
        'domain-record' => 789,
        'site' => 456,
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('SSL certificate destroyed successfully');
});

test('destroy ssl certificate command can be cancelled', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'type' => 'letsencrypt',
                'status' => 'installed',
                'domain' => 'important.com',
                'active' => true,
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySslCertificateCommand::class, [
        'domain-record' => 789,
        'site' => 456,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy the SSL certificate for 'important.com'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');

    $mockClient->assertSentCount(1); // Only the GET request, no destroy
});
