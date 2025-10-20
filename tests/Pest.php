<?php

declare(strict_types=1);

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
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Context;
use Tests\TestCase;
use Thunk\Verbs\Event;

pest()->extends(TestCase::class, LazilyRefreshDatabase::class)
    ->in('Feature', '../packages/*')
    ->beforeEach(fn () => $this->withoutVite());

expect()->extend('toBeIgnoringWhitespace', function (string $expected): void {
    expect(preg_replace('/\s+/', ' ', (string) $this->value))->toBe(preg_replace('/\s+/', ' ', $expected));
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
    $channel = new class extends ChannelState
    {
        public ChannelTypes $type;

        public ?int $owner_id = 123;
    };

    $channel->type = $channel_type;
    Context::add('channel', $channel);

    $member = new class extends MemberState
    {
        public MemberRoles $role;

        public PaymentStates $payment_state;

        public ModerationMemberStates $moderation_state;
    };

    $member->role = $role;
    $member->payment_state = $payment_state;
    $member->moderation_state = $moderation_state;
    // in_channel is deprecated in order to ensure we don't use it in the app itself. Only used for testing.
    $member->in_channel = $switch === ChannelTestSwitches::InChannel;
    $member->owns_channel = $switch === ChannelTestSwitches::OwnsChannel;

    Context::add('active_member', $member);

    $event = new class extends Event
    {
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

/**
 * Get a complete composer.json mock with ALL packages that ANY installation action checks for.
 * This prevents real composer commands from running during tests.
 * This is specifically for agent-os-installer package tests.
 */
function mockComposerJsonWithAllPackages(): array
{
    return [
        'require-dev' => [
            'pestphp/pest' => '^3.0',
            'pestphp/pest-plugin-laravel' => '^3.0',
            'laravel/pint' => '^1.0',
            'larastan/larastan' => '^3.0',
            'rector/rector' => '^2.0',
            'driftingly/rector-laravel' => '^2.0',
            'tightenco/duster' => '^3.0',
            'squizlabs/php_codesniffer' => '^3.0',
            'slevomat/coding-standard' => '^8.0',
            'dealerdirect/phpcodesniffer-composer-installer' => '^1.0',
            'ivqonsanada/enlightn' => '^3.0',
            'barryvdh/laravel-debugbar' => '^3.0',
            'barryvdh/laravel-ide-helper' => '^2.0',
        ],
    ];
}
