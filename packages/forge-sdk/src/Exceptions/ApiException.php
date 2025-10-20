<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Exceptions;

/**
 * Exception thrown when the API returns an error response.
 *
 * Captures HTTP status code, response data, and request details
 * to provide comprehensive error context for debugging.
 */
class ApiException extends ForgeException
{
    protected int $statusCode;

    /**
     * @var array<string, mixed>
     */
    protected array $responseData;

    protected string $endpoint;

    protected string $method;

    /**
     * Create an ApiException from an API error response.
     *
     * @param  array<string, mixed>  $responseData
     * @return static
     */
    public static function fromResponse(
        int $statusCode,
        array $responseData,
        string $endpoint,
        string $method
    ): static {
        $apiMessage = $responseData['message'] ?? 'API request failed';
        $message = "API Error [{$statusCode}] {$method} {$endpoint}: {$apiMessage}";

        /** @var static */
        $exception = new static($message); // @phpstan-ignore new.static
        $exception->statusCode = $statusCode;
        $exception->responseData = $responseData;
        $exception->endpoint = $endpoint;
        $exception->method = $method;

        $exception->context = [
            'status_code' => $statusCode,
            'endpoint' => $endpoint,
            'method' => $method,
            'response' => $responseData,
        ];

        return $exception;
    }

    /**
     * Get the HTTP status code from the API response.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the full response data from the API.
     *
     * @return array<string, mixed>
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }

    /**
     * Get the endpoint that was called.
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Get the HTTP method used.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get validation errors from the response if present.
     *
     * @return array<string, array<int, string>>
     */
    public function getValidationErrors(): array
    {
        return $this->responseData['errors'] ?? [];
    }
}
