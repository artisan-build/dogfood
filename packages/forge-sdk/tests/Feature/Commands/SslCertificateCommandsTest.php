<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Console\Commands\ActivateSslCertificateCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateSslCertificateCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroySslCertificateCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetSslCertificateCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListSslCertificatesCommand;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-sdk.api_token', 'test-token');
    config()->set('forge-sdk.logging.channel', 'null');
});

test('list ssl certificates command provides guidance', function (): void {
    $this->artisan(ListSslCertificatesCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'site' => 456,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetSslCertificateCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'site' => 456,
        'domain-record' => 789,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateSslCertificateCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'site' => 456,
        'domain-record' => 789,
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('SSL certificate created successfully');
});

test('activate ssl certificate command provides guidance', function (): void {
    $this->artisan(ActivateSslCertificateCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'site' => 456,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySslCertificateCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'site' => 456,
        'domain-record' => 789,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySslCertificateCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'site' => 456,
        'domain-record' => 789,
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

    $sdk = app(ForgeSdk::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroySslCertificateCommand::class, [
        'organization' => 'test-org',
        'server' => 123,
        'site' => 456,
        'domain-record' => 789,
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy the SSL certificate for 'important.com'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');

    $mockClient->assertSentCount(1); // Only the GET request, no destroy
});
