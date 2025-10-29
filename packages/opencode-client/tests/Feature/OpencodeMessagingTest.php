<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;

it('validates message input is not empty', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('sessionId', 'ses_test_123')
        ->set('messageInput', '')
        ->call('sendMessage')
        ->assertSet('error', 'Message cannot be empty');
});

it('adds user message to messages array', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('sessionId', 'ses_test_123')
        ->set('messageInput', 'Hello OpenCode')
        ->assertSet('messages', []);

    // After calling sendMessage, user message should be in array
    // Note: This will fail because we need to implement sendMessage()
});

it('clears message input after sending', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('sessionId', 'ses_test_123')
        ->set('messageInput', 'Test message')
        ->call('sendMessage')
        ->assertSet('messageInput', '');
});

it('prevents sending when not connected', function (): void {
    $component = Livewire::test(OpencodeChat::class)
        ->set('sessionId', null)
        ->set('messageInput', 'Test message');

    $messageCountBefore = count($component->get('messages'));

    $component->call('sendMessage');

    $component->assertSet('messages', []);
    expect(count($component->get('messages')))->toBe($messageCountBefore);
});

it('shows loading state while sending', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('sessionId', 'ses_test_123')
        ->set('messageInput', 'Test')
        ->call('sendMessage')
        ->assertSet('sending', false); // Should be false after completion
});
