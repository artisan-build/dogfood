<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Tests\Helpers\MockResponseFactory;
use Saloon\Http\Faking\MockClient;

beforeEach(function (): void {
    $this->sdk = new ForgeSdk;
    $this->mockClient = new MockClient;
});

test('scheduled jobs index returns list of scheduled jobs', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('scheduled-jobs-list'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->scheduledJobs()->organizationsServersScheduledJobsIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filterstatus: null,
        filteruser: null
    );

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toBeArray()
        ->and($response->json('data.0.id'))->toBe('900')
        ->and($response->json('data.0.type'))->toBe('scheduled-jobs')
        ->and($response->json('data.0.attributes.command'))->toBe('php artisan schedule:run')
        ->and($response->json('data.0.attributes.frequency'))->toBe('minutely');
});

test('scheduled jobs show returns single scheduled job', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::load('scheduled-job-show'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->scheduledJobs()->organizationsServersScheduledJobsShow('1', 100, 900);

    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data.id'))->toBe('900')
        ->and($response->json('data.attributes.command'))->toBe('php artisan schedule:run');
});

test('scheduled jobs show returns 404 for non-existent scheduled job', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::notFound('Scheduled job'));

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->scheduledJobs()->organizationsServersScheduledJobsShow('1', 100, 999);

    expect($response->status())->toBe(404)
        ->and($response->json())->toHaveKey('errors');
});

test('scheduled jobs index handles unauthorized requests', function (): void {
    $this->mockClient->addResponse(MockResponseFactory::unauthorized());

    $this->sdk->withMockClient($this->mockClient);

    $response = $this->sdk->scheduledJobs()->organizationsServersScheduledJobsIndex(
        organization: '1',
        server: 100,
        sort: null,
        pagesize: null,
        pagecursor: null,
        filterstatus: null,
        filteruser: null
    );

    expect($response->status())->toBe(401)
        ->and($response->json())->toHaveKey('errors');
});
