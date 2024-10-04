<?php

declare(strict_types=1);

use ArtisanBuild\Hallway\Channels\Enums\ChannelsFixture;
use ArtisanBuild\Hallway\Channels\Events\ChannelNameChanged;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Jetstream\Enums\UsersFixture;
use Illuminate\Auth\Access\AuthorizationException;

describe('change the channel name', function (): void {
    test('admins can change a channel name', function (): void {
        test()->actingAs(UsersFixture::Admin->get());

        $id = ChannelsFixture::FreeOpen->value;


        expect(ChannelState::load($id)->name)->toBe('Free Open');

        ChannelNameChanged::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            name: 'Test Channel',
        );

        expect(ChannelState::load($id)->name)->toBe('Test Channel');
    });

    it('throws if a guest tries to rename a channel', function (): void {
        $id = ChannelsFixture::FreeOpen->value;

        ChannelNameChanged::commit(
            channel_id: $id,
            name: 'Test Channel',
        );
    })->throws(AuthorizationException::class);

    it('throws if a non-admin tries to rename a channel', function ($user): void {
        test()->actingAs($user->get());
        $id = ChannelsFixture::FreeOpen->value;

        ChannelNameChanged::commit(
            channel_id: $id,
            name: 'Test Channel',
        );
    })->throws(AuthorizationException::class)
        ->with(collect(UsersFixture::cases())->filter(fn($user) => UsersFixture::Admin !== $user));
});
