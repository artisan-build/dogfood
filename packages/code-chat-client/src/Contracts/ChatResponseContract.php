<?php

declare(strict_types=1);

namespace ArtisanBuild\CodeChatClient\Contracts;

interface ChatResponseContract
{
    /**
     * Get the response content as a string.
     */
    public function getContent(): string;

    /**
     * Check if the response was successful.
     */
    public function isSuccessful(): bool;

    /**
     * Get any error message if the response failed.
     */
    public function getError(): ?string;

    /**
     * Get metadata about the response (tokens used, model, etc).
     */
    public function getMetadata(): array;

    /**
     * Get tool usage information if any tools were used.
     */
    public function getToolUsage(): array;
}
