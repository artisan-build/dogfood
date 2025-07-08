<?php

namespace ArtisanBuild\CodeChatClient\Contracts;

use ArtisanBuild\CodeChatClient\Enums\MessageRole;

interface ChatMessageContract
{
    /**
     * Get the message content.
     */
    public function getContent(): string;

    /**
     * Get the role of the message sender.
     */
    public function getRole(): MessageRole;

    /**
     * Get the timestamp of the message.
     */
    public function getTimestamp(): \DateTimeInterface;

    /**
     * Get any metadata associated with the message.
     */
    public function getMetadata(): array;

    /**
     * Convert the message to an array.
     */
    public function toArray(): array;
}
