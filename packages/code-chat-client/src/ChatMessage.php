<?php

declare(strict_types=1);

namespace ArtisanBuild\CodeChatClient;

use ArtisanBuild\CodeChatClient\Contracts\ChatMessageContract;
use ArtisanBuild\CodeChatClient\Enums\MessageRole;

class ChatMessage implements ChatMessageContract
{
    public function __construct(
        protected string $content,
        protected MessageRole $role,
        protected \DateTimeInterface $timestamp,
        protected array $metadata = []
    ) {}

    public static function user(string $content, array $metadata = []): self
    {
        return new self($content, MessageRole::USER, new \DateTimeImmutable, $metadata);
    }

    public static function assistant(string $content, array $metadata = []): self
    {
        return new self($content, MessageRole::ASSISTANT, new \DateTimeImmutable, $metadata);
    }

    public static function error(string $content, array $metadata = []): self
    {
        return new self($content, MessageRole::ERROR, new \DateTimeImmutable, $metadata);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getRole(): MessageRole
    {
        return $this->role;
    }

    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'role' => $this->role->value,
            'timestamp' => $this->timestamp->format('c'),
            'metadata' => $this->metadata,
        ];
    }
}
