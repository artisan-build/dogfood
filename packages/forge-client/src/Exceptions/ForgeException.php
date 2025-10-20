<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Exceptions;

use Exception;

/**
 * Base exception for all Forge SDK exceptions.
 *
 * Provides context data storage for debugging and error handling.
 */
class ForgeException extends Exception
{
    /**
     * Additional context data for debugging.
     *
     * @var array<string, mixed>
     */
    protected array $context = [];

    /**
     * Create a new ForgeException with context data.
     *
     * @param  array<string, mixed>  $context
     */
    public static function make(string $message, array $context = []): static
    {
        /** @var static */
        $exception = new static($message); // @phpstan-ignore new.static
        $exception->context = $context;

        return $exception;
    }

    /**
     * Get the context data.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Add a single context value.
     */
    public function addContext(string $key, mixed $value): static
    {
        $this->context[$key] = $value;

        return $this;
    }
}
