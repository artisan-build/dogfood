<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Tests\Helpers;

use RuntimeException;
use Saloon\Http\Faking\MockResponse;

class MockResponseFactory
{
    /**
     * Load a mock response from a JSON fixture file.
     */
    public static function load(string $fixtureName, int $status = 200): MockResponse
    {
        $path = __DIR__.'/../Fixtures/Responses/'.$fixtureName.'.json';

        if (! file_exists($path)) {
            throw new RuntimeException("Mock response fixture not found: {$path}");
        }

        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException("Failed to read mock response fixture: {$path}");
        }

        return new MockResponse($content, $status, [
            'Content-Type' => 'application/vnd.api+json',
        ]);
    }

    /**
     * Create a mock response with custom JSON data.
     */
    public static function make(array $data, int $status = 200): MockResponse
    {
        return new MockResponse(json_encode($data), $status, [
            'Content-Type' => 'application/vnd.api+json',
        ]);
    }

    /**
     * Create an error response.
     */
    public static function error(string $message, int $status = 400): MockResponse
    {
        return self::make([
            'errors' => [
                [
                    'status' => (string) $status,
                    'title' => 'Error',
                    'detail' => $message,
                ],
            ],
        ], $status);
    }

    /**
     * Create a not found response.
     */
    public static function notFound(string $resource = 'Resource'): MockResponse
    {
        return self::error("{$resource} not found", 404);
    }

    /**
     * Create an unauthorized response.
     */
    public static function unauthorized(): MockResponse
    {
        return self::error('Unauthenticated.', 401);
    }

    /**
     * Create a validation error response.
     */
    public static function validationError(array $errors): MockResponse
    {
        return self::make([
            'message' => 'The given data was invalid.',
            'errors' => $errors,
        ], 422);
    }
}
