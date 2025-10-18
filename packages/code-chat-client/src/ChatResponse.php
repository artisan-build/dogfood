<?php

declare(strict_types=1);

namespace ArtisanBuild\CodeChatClient;

use ArtisanBuild\CodeChatClient\Contracts\ChatResponseContract;

class ChatResponse implements ChatResponseContract
{
    public function __construct(
        protected string $content,
        protected bool $successful = true,
        protected ?string $error = null,
        protected array $metadata = [],
        protected array $toolUsage = []
    ) {}

    public static function success(string $content, array $metadata = [], array $toolUsage = []): self
    {
        return new self($content, true, null, $metadata, $toolUsage);
    }

    public static function failure(string $error, string $content = ''): self
    {
        return new self($content, false, $error);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getToolUsage(): array
    {
        return $this->toolUsage;
    }
}
