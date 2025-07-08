<?php

namespace ArtisanBuild\CodeChatClient\Contracts;

interface ChatDriverContract
{
    /**
     * Send a message to the chat service and get a response.
     */
    public function send(string $message, array $options = []): ChatResponseContract;

    /**
     * Stream a message to the chat service with real-time updates.
     */
    public function stream(string $message, callable $callback, array $options = []): void;

    /**
     * Get the name of the driver.
     */
    public function getName(): string;

    /**
     * Check if the driver is available/configured.
     */
    public function isAvailable(): bool;

    /**
     * Get default options for this driver.
     */
    public function getDefaultOptions(): array;
}
