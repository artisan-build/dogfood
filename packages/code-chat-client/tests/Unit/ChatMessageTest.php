<?php

declare(strict_types=1);

use ArtisanBuild\CodeChatClient\ChatMessage;
use ArtisanBuild\CodeChatClient\Enums\MessageRole;

it('creates user messages', function (): void {
    $message = ChatMessage::user('Hello, world!');

    expect($message->getContent())->toBe('Hello, world!')
        ->and($message->getRole())->toBe(MessageRole::USER)
        ->and($message->getTimestamp())->toBeInstanceOf(DateTimeInterface::class);
});

it('creates assistant messages', function (): void {
    $message = ChatMessage::assistant('I can help with that.');

    expect($message->getContent())->toBe('I can help with that.')
        ->and($message->getRole())->toBe(MessageRole::ASSISTANT);
});

it('creates error messages', function (): void {
    $message = ChatMessage::error('Something went wrong');

    expect($message->getContent())->toBe('Something went wrong')
        ->and($message->getRole())->toBe(MessageRole::ERROR);
});

it('converts messages to array', function (): void {
    $message = ChatMessage::user('Test message', ['custom' => 'metadata']);
    $array = $message->toArray();

    expect($array)->toHaveKeys(['content', 'role', 'timestamp', 'metadata'])
        ->and($array['content'])->toBe('Test message')
        ->and($array['role'])->toBe('user')
        ->and($array['metadata'])->toBe(['custom' => 'metadata']);
});
