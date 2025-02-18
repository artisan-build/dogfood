<?php

declare(strict_types=1);

use App\Enums\UsersFixture;
use ArtisanBuild\Hallway\Calendar\Events\GatheringUpdated;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use ArtisanBuild\Hallway\Channels\Enums\ChannelsFixture;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\Events\CommunityChannelUpdated;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use Illuminate\Auth\Access\AuthorizationException;

mutates([
    GatheringUpdated::class,
    GatheringState::class,
]);
describe('change the channel name', function (): void {
    test('owners can change a channel name', function (): void {
        test()->asUser(UsersFixture::Owner->get());

        $id = ChannelsFixture::FreeOpen->value;

        expect(ChannelState::load($id)->name)->toBe('General');

        CommunityChannelUpdated::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            name: 'Test Channel',
        );

        expect(ChannelState::load($id)->name)->toBe('Test Channel');
    });

    test('admins can change a channel name', function (): void {
        test()->asUser(UsersFixture::Admin->get());

        $id = ChannelsFixture::FreeOpen->value;

        expect(ChannelState::load($id)->name)->toBe('General');

        CommunityChannelUpdated::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            name: 'Test Channel',
        );

        expect(ChannelState::load($id)->name)->toBe('Test Channel');
    });

    it('throws if a guest tries to rename a channel', function (): void {
        $id = ChannelsFixture::FreeOpen->value;

        CommunityChannelUpdated::commit(
            channel_id: $id,
            name: 'Test Channel',
        );
    })->throws(AuthorizationException::class);

    it('throws if a non-admin tries to rename a channel', function ($user): void {
        test()->asUser($user->get());
        $id = ChannelsFixture::FreeOpen->value;

        CommunityChannelUpdated::commit(
            channel_id: $id,
            name: 'Test Channel',
        );
    })->throws(AuthorizationException::class)
        ->with(collect(UsersFixture::cases())->filter(fn ($user) => $user !== UsersFixture::Admin && $user !== UsersFixture::Owner));
});

describe('change the channel type', function (): void {

    test('owners can change a channel type', function (): void {
        test()->asUser(UsersFixture::Owner->get());

        $id = ChannelsFixture::FreeOpen->value;

        expect(ChannelState::load($id)->type)->toBe(ChannelTypes::OpenFree);

        CommunityChannelUpdated::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            type: ChannelTypes::PrivateFree,
        );

        expect(ChannelState::load($id)->type)->toBe(ChannelTypes::PrivateFree);
    });

    test('admins can change a channel type', function (): void {
        test()->asUser(UsersFixture::Admin->get());

        $id = ChannelsFixture::FreeOpen->value;

        expect(ChannelState::load($id)->type)->toBe(ChannelTypes::OpenFree);

        CommunityChannelUpdated::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            type: ChannelTypes::PrivateFree,
        );

        expect(ChannelState::load($id)->type)->toBe(ChannelTypes::PrivateFree);
    });

    it('throws if a guest tries to change type on a channel', function (): void {
        $id = ChannelsFixture::FreeOpen->value;

        CommunityChannelUpdated::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            type: ChannelTypes::PrivateFree,
        );
    })->throws(AuthorizationException::class);

    it('throws if a non-admin tries to change type on a channel', function ($user): void {
        test()->asUser($user->get());
        $id = ChannelsFixture::FreeOpen->value;

        CommunityChannelUpdated::commit(
            channel_id: ChannelsFixture::FreeOpen->value,
            type: ChannelTypes::PrivateFree,
        );
    })->throws(AuthorizationException::class)
        ->with(collect(UsersFixture::cases())->filter(fn ($user) => $user !== UsersFixture::Admin && $user !== UsersFixture::Owner));
});
