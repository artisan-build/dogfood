<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Exceptions;

/**
 * Exception thrown when authentication fails (HTTP 401 or 403 response).
 *
 * Provides helpful troubleshooting information for authentication issues.
 */
class AuthenticationException extends ApiException
{
    /**
     * Create an AuthenticationException for 401 Unauthenticated response.
     *
     * @return static
     */
    public static function unauthenticated(string $endpoint, string $method): static
    {
        $message = "Authentication failed for {$method} {$endpoint}. "
            .'Please verify your API token is valid and has not expired. '
            .'Check your FORGE_API_TOKEN environment variable.';

        /** @var static */
        $exception = new static($message); // @phpstan-ignore new.static
        $exception->statusCode = 401;
        $exception->responseData = ['message' => 'Unauthenticated'];
        $exception->endpoint = $endpoint;
        $exception->method = $method;

        $exception->context = [
            'status_code' => 401,
            'endpoint' => $endpoint,
            'method' => $method,
            'help' => 'Verify FORGE_API_TOKEN is set and valid',
        ];

        return $exception;
    }

    /**
     * Create an AuthenticationException for 403 Forbidden response.
     *
     * @return static
     */
    public static function forbidden(string $endpoint, string $method, ?string $message = null): static
    {
        $customMessage = $message ?? 'You do not have permission to access this resource';
        $fullMessage = "Forbidden: {$customMessage} [{$method} {$endpoint}]";

        /** @var static */
        $exception = new static($fullMessage); // @phpstan-ignore new.static
        $exception->statusCode = 403;
        $exception->responseData = ['message' => $customMessage];
        $exception->endpoint = $endpoint;
        $exception->method = $method;

        $exception->context = [
            'status_code' => 403,
            'endpoint' => $endpoint,
            'method' => $method,
            'reason' => $customMessage,
        ];

        return $exception;
    }
}
