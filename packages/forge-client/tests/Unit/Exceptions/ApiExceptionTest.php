<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Exceptions\ApiException;
use ArtisanBuild\ForgeClient\Exceptions\ForgeException;

test('api exception extends forge exception', function (): void {
    $exception = new ApiException('API error');

    expect($exception)->toBeInstanceOf(ForgeException::class);
});

test('can create api exception with status code and response', function (): void {
    $responseData = ['message' => 'Server not found', 'errors' => []];
    $exception = ApiException::fromResponse(
        statusCode: 404,
        responseData: $responseData,
        endpoint: '/api/v1/servers/123',
        method: 'GET'
    );

    expect($exception->getStatusCode())->toBe(404)
        ->and($exception->getResponseData())->toBe($responseData)
        ->and($exception->getEndpoint())->toBe('/api/v1/servers/123')
        ->and($exception->getMethod())->toBe('GET');
});

test('formats error message from api response', function (): void {
    $responseData = ['message' => 'Validation failed', 'errors' => ['name' => ['The name field is required']]];
    $exception = ApiException::fromResponse(
        statusCode: 422,
        responseData: $responseData,
        endpoint: '/api/v1/servers',
        method: 'POST'
    );

    expect($exception->getMessage())->toContain('422')
        ->and($exception->getMessage())->toContain('POST')
        ->and($exception->getMessage())->toContain('/api/v1/servers')
        ->and($exception->getMessage())->toContain('Validation failed');
});

test('handles empty response data gracefully', function (): void {
    $exception = ApiException::fromResponse(
        statusCode: 500,
        responseData: [],
        endpoint: '/api/v1/servers',
        method: 'POST'
    );

    expect($exception->getMessage())->toContain('500')
        ->and($exception->getResponseData())->toBe([]);
});

test('extracts validation errors from response', function (): void {
    $responseData = [
        'message' => 'Validation failed',
        'errors' => [
            'name' => ['The name field is required'],
            'size' => ['The size field is required'],
        ],
    ];
    $exception = ApiException::fromResponse(422, $responseData, '/api/v1/servers', 'POST');

    $errors = $exception->getValidationErrors();

    expect($errors)->toBe([
        'name' => ['The name field is required'],
        'size' => ['The size field is required'],
    ]);
});

test('returns empty array when no validation errors present', function (): void {
    $exception = ApiException::fromResponse(500, ['message' => 'Server error'], '/api/v1/servers', 'GET');

    expect($exception->getValidationErrors())->toBe([]);
});
