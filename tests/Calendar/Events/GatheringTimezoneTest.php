<?php

declare(strict_types=1);

use App\Enums\UsersFixture;
use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use ArtisanBuild\Hallway\Calendar\Events\GatheringCreated;
use ArtisanBuild\Hallway\Calendar\Models\Gathering;
use ArtisanBuild\Hallway\Members\MemberTimezoneUpdated;

it('sets the correct time in UTC', function (): void {
    test()->asUser(UsersFixture::Admin->get());
    Context::add('active_user', UsersFixture::Admin->get()->hallway_members->first());
    MemberTimezoneUpdated::commit(
        member_id: UsersFixture::Admin->get()->hallway_members->first()->id,
        timezone: 'Asia/Tokyo',
    );

    $gathering_id = snowflake_id();
    GatheringCreated::commit(
        gathering_id: $gathering_id,
        title: 'Test Gathering',
        description: 'Gathering created during a test',
        start: now()->addDay()->hour(13)->minute(0)->second(0)->millisecond(0),
        timezone: 'Asia/Tokyo',
        duration: 60,
        invitation_level: InvitationLevels::Free,
    );

    $gathering = Gathering::find($gathering_id);

    expect($gathering->start->format('Y-m-d\TH:i'))
        ->toBe(now()->addDay()->hour(13)->minute(0)->second(0)->millisecond(0)->subHours(9)->format('Y-m-d\TH:i'))
        ->and($gathering->end->format('Y-m-d\TH:i'))
        ->toBe(now()->addDay()->hour(13)->minute(0)->second(0)->millisecond(0)->subHours(8)->format('Y-m-d\TH:i'));

    $localized = $gathering->verbs_state()->forMember(Illuminate\Support\Facades\Context::get('active_member'));

    expect($localized->start->format('Y-m-d\TH:i'))->toBe(now()->addDay()->hour(13)->minute(0)->second(0)->millisecond(0)->format('Y-m-d\TH:i'))
        ->and($localized->end->format('Y-m-d\TH:i'))->toBe(now()->addDay()->hour(13)->minute(0)->second(0)->millisecond(0)->addHours(1)->format('Y-m-d\TH:i'));


});
