<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use Livewire\Livewire;

it('can instantiate component', function (): void {
    $component = Livewire::test(OpencodeChat::class);

    expect($component)->not->toBeNull();
});

it('initializes with default server url', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSet('serverUrl', 'http://localhost:3333');
});

it('initializes with empty messages array', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSet('messages', []);
});

it('initializes with null session id', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSet('sessionId', null);
});

it('initializes with false connecting state', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSet('connecting', false);
});

it('initializes with false sending state', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSet('sending', false);
});

it('initializes with empty message input', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSet('messageInput', '');
});

it('initializes with null error', function (): void {
    Livewire::test(OpencodeChat::class)
        ->assertSet('error', null);
});
