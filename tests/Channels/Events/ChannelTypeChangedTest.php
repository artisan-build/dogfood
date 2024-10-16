<?php

declare(strict_types=1);

use App\Enums\UsersFixture;
use ArtisanBuild\Hallway\Channels\Enums\ChannelsFixture;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\Events\ChannelTypeChanged;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use Illuminate\Auth\Access\AuthorizationException;

describe('change the channel type', function (): void {

    test('owners can change a channel type', function (): void {
        test()->actingAs(UsersFixture::Owner->get());

        $id = ChannelsFixture::FreeOpen->value;


        expect(ChannelState::load($id)->type)->toBe(ChannelTypes::OpenFree);

        ChannelTypeChanged::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            type: ChannelTypes::PrivateFree,
        );

        expect(ChannelState::load($id)->type)->toBe(ChannelTypes::PrivateFree);
    });

    test('admins can change a channel type', function (): void {
        test()->actingAs(UsersFixture::Admin->get());

        $id = ChannelsFixture::FreeOpen->value;


        expect(ChannelState::load($id)->type)->toBe(ChannelTypes::OpenFree);

        ChannelTypeChanged::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            type: ChannelTypes::PrivateFree,
        );

        expect(ChannelState::load($id)->type)->toBe(ChannelTypes::PrivateFree);
    });

    it('throws if a guest tries to change type on a channel', function (): void {
        $id = ChannelsFixture::FreeOpen->value;

        ChannelTypeChanged::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            type: ChannelTypes::PrivateFree,
        );
    })->throws(AuthorizationException::class);

    it('throws if a non-admin tries to change type on a channel', function ($user): void {
        test()->actingAs($user->get());
        $id = ChannelsFixture::FreeOpen->value;

        ChannelTypeChanged::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            type: ChannelTypes::PrivateFree,
        );
    })->throws(AuthorizationException::class)
        ->with(collect(UsersFixture::cases())->filter(fn($user) => UsersFixture::Admin !== $user && UsersFixture::Owner !== $user));
});
