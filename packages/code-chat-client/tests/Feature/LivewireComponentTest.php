<?php

declare(strict_types=1);

use ArtisanBuild\CodeChatClient\ChatManager;
use ArtisanBuild\CodeChatClient\ChatResponse;
use ArtisanBuild\CodeChatClient\Contracts\ChatDriverContract;
use ArtisanBuild\CodeChatClient\Livewire\CodeChatComponent;
use Livewire\Livewire;

it('renders the chat component', function (): void {
    // Mock a driver to avoid the select dropdown
    $mockDriver = Mockery::mock(ChatDriverContract::class);
    $mockDriver->shouldReceive('isAvailable')->andReturn(true);
    $mockDriver->shouldReceive('getName')->andReturn('Test Driver');

    $manager = app(ChatManager::class);
    $manager->extend('test', fn () => $mockDriver);

    config()->set('code-chat-client.default', 'test');

    Livewire::test(CodeChatComponent::class)
        ->assertSee('Start a conversation')
        ->assertSee('Type your message...');
});

it('sends messages and displays responses', function (): void {
    config()->set('code-chat-client.streaming', false);

    $mockDriver = Mockery::mock(ChatDriverContract::class);
    $mockDriver->shouldReceive('isAvailable')->andReturn(true);
    $mockDriver->shouldReceive('getName')->andReturn('Test Driver');
    $mockDriver->shouldReceive('getDefaultOptions')->andReturn([]);
    $mockDriver->shouldReceive('send')
        ->with('Hello', [])
        ->andReturn(ChatResponse::success('Hi there!'));

    $manager = app(ChatManager::class);
    $manager->extend('test', fn () => $mockDriver);

    Livewire::test(CodeChatComponent::class, ['driver' => 'test'])
        ->set('message', 'Hello')
        ->call('sendMessage')
        ->assertSee('Hello')
        ->assertSee('Hi there!')
        ->assertSet('message', '');
});

it('handles errors gracefully', function (): void {
    config()->set('code-chat-client.streaming', false);

    $mockDriver = Mockery::mock(ChatDriverContract::class);
    $mockDriver->shouldReceive('isAvailable')->andReturn(true);
    $mockDriver->shouldReceive('getName')->andReturn('Test Driver');
    $mockDriver->shouldReceive('getDefaultOptions')->andReturn([]);
    $mockDriver->shouldReceive('send')
        ->andThrow(new Exception('API Error'));

    $manager = app(ChatManager::class);
    $manager->extend('test', fn () => $mockDriver);

    Livewire::test(CodeChatComponent::class, ['driver' => 'test'])
        ->set('message', 'Hello')
        ->call('sendMessage')
        ->assertSee('Error: API Error');
});

it('can clear chat history', function (): void {
    Livewire::test(CodeChatComponent::class)
        ->set('messages', [
            ['content' => 'Test 1', 'role' => 'user'],
            ['content' => 'Test 2', 'role' => 'assistant'],
        ])
        ->assertSee('Test 1')
        ->assertSee('Test 2')
        ->call('clearChat')
        ->assertSet('messages', [])
        ->assertSet('message', '');
});
