<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeSdk;
    $this->mockClient = new MockClient;
});

test('firewall rules index returns list of firewall rules', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('firewall-rules-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->firewallRules()->organizationsServersFirewallRulesIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filtername: null,
        filterstatus: null,
        filteripAddress: null,
        filtertype: null,
        filterport: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data'))->toHaveCount(3)
        ->and($response->json('data.0.id'))->toBe('800')
        ->and($response->json('data.0.type'))->toBe('firewall-rules')
        ->and($response->json('data.0.attributes.name'))->toBe('Allow SSH')
        ->and($response->json('data.0.attributes.port'))->toBe(22)
        ->and($response->json('data.0.attributes.type'))->toBe('allow')
        ->and($response->json('data.0.attributes.ip_address'))->toBe('0.0.0.0/0')
        ->and($response->json('data.0.attributes.status'))->toBe('installed')
        ->and($response->json('data.1.attributes.name'))->toBe('Allow HTTP')
        ->and($response->json('data.1.attributes.port'))->toBe(80)
        ->and($response->json('data.2.attributes.name'))->toBe('Allow HTTPS')
        ->and($response->json('data.2.attributes.port'))->toBe(443);
});

test('firewall rules show returns single firewall rule', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('firewall-rule-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->firewallRules()->organizationsServersFirewallRulesShow('1', 100, 800);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.id'))->toBe('800')
        ->and($response->json('data.type'))->toBe('firewall-rules')
        ->and($response->json('data.attributes.name'))->toBe('Allow SSH')
        ->and($response->json('data.attributes.port'))->toBe(22)
        ->and($response->json('data.attributes.type'))->toBe('allow')
        ->and($response->json('data.attributes.ip_address'))->toBe('0.0.0.0/0')
        ->and($response->json('data.attributes.status'))->toBe('installed');
});

test('firewall rules show returns 404 for non-existent firewall rule', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Firewall rule'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->firewallRules()->organizationsServersFirewallRulesShow('1', 100, 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('firewall rules destroy deletes a firewall rule', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::make([], 204));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->firewallRules()->organizationsServersFirewallRulesDestroy('1', 100, 800);

    expect($response->status())->toBe(204);
});

test('firewall rules index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->firewallRules()->organizationsServersFirewallRulesIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filtername: null,
        filterstatus: null,
        filteripAddress: null,
        filtertype: null,
        filterport: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
