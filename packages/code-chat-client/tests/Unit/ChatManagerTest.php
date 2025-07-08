<?php

use ArtisanBuild\CodeChatClient\ChatManager;
use ArtisanBuild\CodeChatClient\Contracts\ChatDriverContract;

it('can create a chat manager instance', function (): void {
    $manager = app(ChatManager::class);

    expect($manager)->toBeInstanceOf(ChatManager::class);
});

it('can extend the manager with custom drivers', function (): void {
    $manager = app(ChatManager::class);

    $mockDriver = Mockery::mock(ChatDriverContract::class);
    $mockDriver->shouldReceive('isAvailable')->andReturn(true);
    $mockDriver->shouldReceive('getName')->andReturn('custom-driver');

    $manager->extend('custom', fn () => $mockDriver);

    $driver = $manager->driver('custom');

    expect($driver)->toBe($mockDriver);
});

it('returns available drivers', function (): void {
    $manager = app(ChatManager::class);

    $mockDriver = Mockery::mock(ChatDriverContract::class);
    $mockDriver->shouldReceive('isAvailable')->andReturn(true);
    $mockDriver->shouldReceive('getName')->andReturn('Test Driver');

    $manager->extend('test', fn () => $mockDriver);

    $drivers = $manager->getAvailableDrivers();

    expect($drivers)->toHaveKey('test')
        ->and($drivers['test'])->toBe('Test Driver');
});
