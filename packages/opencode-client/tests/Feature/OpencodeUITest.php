<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;

it('renders server url input field', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSee('Server URL')
        ->assertSeeHtml('wire:model="serverUrl"');
});

it('renders connect button', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSee('Connect')
        ->assertSeeHtml('wire:click="connect"');
});

it('renders message input area', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSeeHtml('wire:model="messageInput"');
});

it('renders send button', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSee('Send')
        ->assertSeeHtml('wire:click="sendMessage"');
});

it('shows loading indicator when connecting', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('connecting', true)
        ->assertSee('Connecting');
});

it('shows loading indicator when sending', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('sending', true)
        ->assertSee('Sending');
});

it('displays error message', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('error', 'Test error message')
        ->assertSee('Test error message');
});

it('displays messages', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('messages', [
            ['role' => 'user', 'content' => 'Hello'],
            ['role' => 'assistant', 'content' => 'Hi there!'],
        ])
        ->assertSee('Hello')
        ->assertSee('Hi there!');
});

it('disables send button when not connected', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSet('sessionId', null)
        ->assertSeeHtml('disabled');
});
