<?php

declare(strict_types=1);

use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\Events\ChannelCreated;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Jetstream\Enums\UsersFixture;
use Illuminate\Auth\Access\AuthorizationException;

describe('Channel creation', function (): void {
    test('admins can create a channel', function (): void {
        test()->actingAs(UsersFixture::Admin->get());

        $id = snowflake_id();

        ChannelCreated::commit(
            channel_id: $id,
            name: 'Test Channel',
            type: ChannelTypes::OpenFree,
        );

        expect(ChannelState::load($id))->toBeInstanceOf(ChannelState::class)
            ->and(ChannelState::load($id)->name)->toBe('Test Channel')
            ->and(ChannelState::load($id)->type)->toBe(ChannelTypes::OpenFree);

    });

    it('throws if a guest tries to create a channel', function (): void {
        $id = snowflake_id();

        ChannelCreated::commit(
            channel_id: $id,
            name: 'Test Channel',
            type: ChannelTypes::OpenFree,
        );
    })->throws(AuthorizationException::class);


    it('throws if any non-admin user tries to create a channel', function ($user): void {
        test()->actingAs($user->get());
        $id = snowflake_id();

        ChannelCreated::commit(
            channel_id: $id,
            name: 'Test Channel',
            type: ChannelTypes::OpenFree,
        );
    })->throws(AuthorizationException::class)->with(collect(UsersFixture::cases())->filter(fn($case) => UsersFixture::Admin !== $case));

});
