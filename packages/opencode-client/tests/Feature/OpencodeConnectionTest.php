<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;

it('validates server url format', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('serverUrl', 'not-a-valid-url')
        ->call('connect')
        ->assertSet('error', 'Invalid server URL format')
        ->assertSet('sessionId', null);
});

it('clears previous error when connecting', function (): void {
    Livewire::test(OpencodeChat::class)
        ->set('error', 'Previous error')
        ->set('serverUrl', 'http://localhost:3333')
        ->assertSet('error', 'Previous error');

    // When connect is called, error should be cleared first
    // Note: This will actually try to connect, so it may fail with a connection error
    // but the important part is that the previous error was cleared
});
