<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Exceptions;

/**
 * Exception thrown when rate limit is exceeded (HTTP 429 response).
 */
class RateLimitException extends ApiException {}
