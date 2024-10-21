<?php

declare(strict_types=1);

use App\Enums\UsersFixture;
use ArtisanBuild\Hallway\Channels\Enums\ChannelsFixture;
use ArtisanBuild\Hallway\Channels\Events\CommunityChannelNameChanged;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use Illuminate\Auth\Access\AuthorizationException;

describe('change the channel name', function (): void {
    test('owners can change a channel name', function (): void {
        test()->asUser(UsersFixture::Owner->get());

        $id = ChannelsFixture::FreeOpen->value;


        expect(ChannelState::load($id)->name)->toBe('Free Open');

        CommunityChannelNameChanged::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            name: 'Test Channel',
        );

        expect(ChannelState::load($id)->name)->toBe('Test Channel');
    });

    test('admins can change a channel name', function (): void {
        test()->asUser(UsersFixture::Admin->get());

        $id = ChannelsFixture::FreeOpen->value;


        expect(ChannelState::load($id)->name)->toBe('Free Open');

        CommunityChannelNameChanged::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            name: 'Test Channel',
        );

        expect(ChannelState::load($id)->name)->toBe('Test Channel');
    });

    it('throws if a guest tries to rename a channel', function (): void {
        $id = ChannelsFixture::FreeOpen->value;

        CommunityChannelNameChanged::commit(
            channel_id: $id,
            name: 'Test Channel',
        );
    })->throws(AuthorizationException::class);

    it('throws if a non-admin tries to rename a channel', function ($user): void {
        test()->asUser($user->get());
        $id = ChannelsFixture::FreeOpen->value;

        CommunityChannelNameChanged::commit(
            channel_id: $id,
            name: 'Test Channel',
        );
    })->throws(AuthorizationException::class)
        ->with(collect(UsersFixture::cases())->filter(fn($user) => UsersFixture::Admin !== $user && UsersFixture::Owner !== $user));
});
