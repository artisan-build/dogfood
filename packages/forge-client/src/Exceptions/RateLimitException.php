<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Exceptions;

/**
 * Exception thrown when rate limit is exceeded (HTTP 429 response).
 *
 * Provides retry-after information to help applications implement
 * proper backoff strategies when rate limits are hit.
 */
class RateLimitException extends ApiException
{
    protected ?int $retryAfterSeconds = null;

    protected ?int $retryAt = null;

    /**
     * Create a RateLimitException from a 429 API response.
     */
    public static function fromRetryAfter(
        ?int $retryAfterSeconds,
        string $endpoint,
        string $method
    ): static {
        $retryMessage = $retryAfterSeconds
            ? "Retry after {$retryAfterSeconds} seconds."
            : 'Please try again later.';

        $message = "Rate limit exceeded for {$method} {$endpoint}. {$retryMessage}";

        /** @var static */
        $exception = new static($message); // @phpstan-ignore new.static
        $exception->statusCode = 429;
        $exception->responseData = ['message' => 'Rate limit exceeded'];
        $exception->endpoint = $endpoint;
        $exception->method = $method;
        $exception->retryAfterSeconds = $retryAfterSeconds;
        $exception->retryAt = $retryAfterSeconds ? time() + $retryAfterSeconds : null;

        $exception->context = [
            'status_code' => 429,
            'endpoint' => $endpoint,
            'method' => $method,
            'retry_after_seconds' => $retryAfterSeconds,
            'retry_at' => $exception->retryAt,
        ];

        return $exception;
    }

    /**
     * Get the number of seconds to wait before retrying.
     */
    public function getRetryAfterSeconds(): ?int
    {
        return $this->retryAfterSeconds;
    }

    /**
     * Get the Unix timestamp when retry is allowed.
     */
    public function getRetryAt(): ?int
    {
        return $this->retryAt;
    }

    /**
     * Check if retry is allowed now.
     */
    public function canRetryNow(): bool
    {
        if ($this->retryAt === null) {
            return true;
        }

        return time() >= $this->retryAt;
    }
}
