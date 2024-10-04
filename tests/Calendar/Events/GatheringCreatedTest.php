<?php

declare(strict_types=1);

use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use ArtisanBuild\Hallway\Calendar\Events\GatheringCreated;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use ArtisanBuild\Jetstream\Enums\UsersFixture;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;

mutates([GatheringCreated::class, GatheringState::class]);

describe('event created', function (): void {
    test('admin can create an event', function (): void {
        test()->actingAs(UsersFixture::Admin->get());

        // It seems unlikely that the test would start before midnight and end after midnight, but  why take the chance?
        Carbon::setTestNow(now()->startOfDay());

        $gathering_id = snowflake_id();
        GatheringCreated::commit(
            gathering_id: $gathering_id,
            title: 'Test Gathering',
            description: 'Gathering created during a test',
            start: now()->addDay()->hour(13),
            end: now()->addDay()->hour(14),
            invitation_level: InvitationLevels::Free,
        );

        $state = GatheringState::load($gathering_id);

        expect($state)
            ->toBeInstanceOf(GatheringState::class)
            ->and($state->title)->toBe('Test Gathering')
            ->and($state->description)->toBe('Gathering created during a test')
            ->and($state->start->toDateTimeString())->toBe(now()->addDay()->hour(13)->toDateTimeString())
            ->and($state->end->toDateTimeString())->toBe(now()->addDay()->hour(14)->toDateTimeString())
            ->and($state->invitation_level)->toBe(InvitationLevels::Free);

    });

    it('throws if a guest tries to create a gathering', function (): void {
        $gathering_id = snowflake_id();

        GatheringCreated::commit(
            gathering_id: $gathering_id,
            title: 'Test Gathering',
            description: 'Gathering created during a test',
            start: now()->addDay()->hour(13),
            end: now()->addDay()->hour(14),
            invitation_level: InvitationLevels::Free,
        );
    })->throws(AuthorizationException::class);


    it('throws if any non-admin user tries to create a gathering', function ($user): void {
        test()->actingAs($user->get());
        $gathering_id = snowflake_id();

        GatheringCreated::commit(
            gathering_id: $gathering_id,
            title: 'Test Gathering',
            description: 'Gathering created during a test',
            start: now()->addDay()->hour(13),
            end: now()->addDay()->hour(14),
            invitation_level: InvitationLevels::Free,
        );
    })->throws(AuthorizationException::class)->with(collect(UsersFixture::cases())->filter(fn($case) => UsersFixture::Admin !== $case));
});

