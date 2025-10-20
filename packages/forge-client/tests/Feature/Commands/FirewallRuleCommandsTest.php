<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Console\Commands\CreateFirewallRuleCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyFirewallRuleCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetFirewallRuleCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListFirewallRulesCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    config()->set('forge-client.api_token', 'test-token');
    config()->set('forge-client.logging.channel', 'null');
});

test('list firewall rules command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Allow SSH',
                    'type' => 'allow',
                    'ip_address' => '0.0.0.0',
                    'port' => '22',
                    'status' => 'installed',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
                [
                    'id' => 2,
                    'name' => 'Allow HTTP',
                    'type' => 'allow',
                    'ip_address' => '0.0.0.0',
                    'port' => '80',
                    'status' => 'installed',
                    'created_at' => '2024-01-02T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListFirewallRulesCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Allow SSH')
        ->expectsOutputToContain('Allow HTTP')
        ->expectsOutputToContain('Listed 2 firewall rule(s)');
});

test('list firewall rules command handles filters', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Allow SSH',
                    'type' => 'allow',
                    'ip_address' => '192.168.1.1',
                    'port' => '22',
                    'status' => 'installed',
                    'created_at' => '2024-01-01T00:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(ListFirewallRulesCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
        '--filter-type' => 'allow',
        '--filter-port' => '22',
    ])
        ->assertExitCode(0);

    $mockClient->assertSentCount(1);
});

test('get firewall rule command executes successfully', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'Allow SSH',
                'type' => 'allow',
                'ip_address' => '0.0.0.0',
                'port' => '22',
                'status' => 'installed',
                'created_at' => '2024-01-01T00:00:00Z',
                'updated_at' => '2024-01-01T00:00:00Z',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(GetFirewallRuleCommand::class, [
        'rule' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Allow SSH')
        ->expectsOutputToContain('allow')
        ->expectsOutputToContain('22');
});

test('create firewall rule command executes successfully with confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'New Rule',
                'type' => 'allow',
                'ip_address' => '0.0.0.0',
                'port' => '443',
                'status' => 'installing',
            ],
        ], 201),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(CreateFirewallRuleCommand::class, [
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Firewall rule created successfully');
});

test('destroy firewall rule command requires confirmation', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'Test Rule',
                'status' => 'installed',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyFirewallRuleCommand::class, [
        'rule' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy firewall rule 'Test Rule'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');
});

test('destroy firewall rule command executes with confirmation skip', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'Test Rule',
                'status' => 'installed',
            ],
        ], 200),
        MockResponse::make([], 204),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyFirewallRuleCommand::class, [
        'rule' => 1,
        'server' => 123,
        'organization' => 'test-org',
        '--dangerously-skip-confirmation' => true,
    ])
        ->assertExitCode(0)
        ->expectsOutputToContain('Firewall rule destroyed successfully');
});

test('destroy firewall rule command can be cancelled', function (): void {
    $mockClient = new MockClient([
        MockResponse::make([
            'data' => [
                'id' => 1,
                'name' => 'Important Rule',
                'status' => 'installed',
            ],
        ], 200),
    ]);

    $sdk = app(ForgeClient::class);
    $sdk->withMockClient($mockClient);

    $this->artisan(DestroyFirewallRuleCommand::class, [
        'rule' => 1,
        'server' => 123,
        'organization' => 'test-org',
    ])
        ->expectsConfirmation("Type 'yes' to confirm you want to destroy firewall rule 'Important Rule'", 'no')
        ->assertExitCode(0)
        ->expectsOutputToContain('Operation cancelled');

    $mockClient->assertSentCount(1); // Only the GET request, no destroy
});
