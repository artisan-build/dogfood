<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Models\User;
use ArtisanBuild\Hallway\Channels\Enums\ChannelPermissionTypes;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTestSwitches;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Moderation\Enums\ModerationMemberStates;
use ArtisanBuild\Hallway\Payment\Enums\PaymentStates;
use ArtisanBuild\Hallway\Testing\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Context;
use Tests\TestCase;

pest()->extends(TestCase::class, LazilyRefreshDatabase::class)
    ->in('Feature', '../packages/*')
    ->beforeEach(fn () => $this->withoutVite());

expect()->extend('toBeIgnoringWhitespace', function (string $expected): void {
    expect(trim((string) preg_replace('/\s\s+/', ' ', (string) $this->value)))->toBe(trim((string) preg_replace('/\s\s+/', ' ', $expected)));
});

function channel_permissions(
    ChannelTypes $channel_type,
    ChannelPermissionTypes $permission_type,
    MemberRoles $role,
    PaymentStates $payment_state,
    ModerationMemberStates $moderation_state,
    ChannelTestSwitches $switch,
    bool $expected,
): void {
    $channel = new class () extends ChannelState {
        public ChannelTypes $type;
        public int|null $owner_id = 123;
    };

    $channel->type = $channel_type;
    Context::add('channel', $channel);

    $member = new class () extends MemberState {
        public MemberRoles $role;
        public PaymentStates $payment_state;
        public ModerationMemberStates $moderation_state;
    };

    $member->role = $role;
    $member->payment_state = $payment_state;
    $member->moderation_state = $moderation_state;
    // in_channel is deprecated in order to ensure we don't use it in the app itself. Only used for testing.
    $member->in_channel = ChannelTestSwitches::InChannel === $switch;
    $member->owns_channel = ChannelTestSwitches::OwnsChannel === $switch;

    Illuminate\Support\Facades\Context::add('active_member', $member);


    $event = new class () extends Thunk\Verbs\Event {
        public ChannelPermissionTypes $needs_channel_permissions;
    };

    $event->needs_channel_permissions = $permission_type;

    expect($member->can($event))->toBe($expected);
}

function asUser(User $user): User
{
    test()->actingAs($user);

    Context::add('active_member', $user->hallway_members->first()->verbs_state());

    return $user;
}
