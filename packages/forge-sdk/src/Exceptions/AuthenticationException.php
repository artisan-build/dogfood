<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Exceptions;

/**
 * Exception thrown when authentication fails (HTTP 401 response).
 */
class AuthenticationException extends ApiException {}
