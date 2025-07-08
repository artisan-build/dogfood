<?php

namespace ArtisanBuild\CodeChatClient\Drivers;

use ArtisanBuild\ClaudeCode\Facades\ClaudeCode;
use ArtisanBuild\ClaudeCode\Messages\AssistantMessage;
use ArtisanBuild\ClaudeCode\Messages\Message;
use ArtisanBuild\ClaudeCode\Messages\ResultMessage;
use ArtisanBuild\CodeChatClient\ChatResponse;
use ArtisanBuild\CodeChatClient\Contracts\ChatDriverContract;
use ArtisanBuild\CodeChatClient\Contracts\ChatResponseContract;

class ClaudeCodeDriver implements ChatDriverContract
{
    public function send(string $message, array $options = []): ChatResponseContract
    {
        try {
            $query = ClaudeCode::query($message);

            if (isset($options['model'])) {
                $query->withModel($options['model']);
            }

            if (isset($options['system_prompt'])) {
                $query->withSystemPrompt($options['system_prompt']);
            }

            if (isset($options['max_turns'])) {
                $query->withMaxTurns($options['max_turns']);
            }

            if (isset($options['working_directory'])) {
                $query->withWorkingDirectory($options['working_directory']);
            }

            if (isset($options['allowed_tools'])) {
                $query->allowTools($options['allowed_tools']);
            }

            $messages = $query->execute();

            $content = '';
            $toolUsage = [];
            $metadata = [];

            foreach ($messages as $msg) {
                if ($msg instanceof AssistantMessage) {
                    $content .= $msg->getTextContent()."\n";

                    if ($msg->hasToolUse()) {
                        $toolUsage = array_merge($toolUsage, $msg->getToolUses());
                    }
                }

                if ($msg instanceof ResultMessage && ! $msg->success) {
                    return ChatResponse::failure($msg->error ?? 'Command failed', $content);
                }
            }

            return ChatResponse::success(trim($content), $metadata, $toolUsage);

        } catch (\Exception $e) {
            return ChatResponse::failure($e->getMessage());
        }
    }

    public function stream(string $message, callable $callback, array $options = []): void
    {
        try {
            $query = ClaudeCode::query($message);

            if (isset($options['model'])) {
                $query->withModel($options['model']);
            }

            if (isset($options['system_prompt'])) {
                $query->withSystemPrompt($options['system_prompt']);
            }

            if (isset($options['max_turns'])) {
                $query->withMaxTurns($options['max_turns']);
            }

            if (isset($options['working_directory'])) {
                $query->withWorkingDirectory($options['working_directory']);
            }

            if (isset($options['allowed_tools'])) {
                $query->allowTools($options['allowed_tools']);
            }

            $query->stream(function (Message $message) use ($callback): void {
                if ($message instanceof AssistantMessage) {
                    $callback($message->getTextContent());
                }
            });

        } catch (\Exception $e) {
            $callback('[ERROR] '.$e->getMessage());
        }
    }

    public function getName(): string
    {
        return 'claude-code';
    }

    public function isAvailable(): bool
    {
        try {
            // Check if Claude Code is bound in the container
            return app()->bound('claude-code');
        } catch (\Exception) {
            return false;
        }
    }

    public function getDefaultOptions(): array
    {
        return [
            'model' => 'claude-3-5-sonnet-20241022',
            'max_turns' => 1,
            'allowed_tools' => [],
        ];
    }
}
