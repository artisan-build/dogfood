<?php

declare(strict_types=1);

use ArtisanBuild\Hallway\Channels\Enums\ChannelPermissionTypes;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTestSwitches;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Moderation\Enums\ModerationMemberStates;
use ArtisanBuild\Hallway\Payment\Enums\PaymentStates;

test('read permissions for blog channels', function (
    MemberRoles $role,
    PaymentStates $payment_state,
    ModerationMemberStates $moderation_state,
    bool $expected,
): void {
    channel_permissions(
        channel_type: ChannelTypes::Blog,
        permission_type: ChannelPermissionTypes::Read,
        role: $role,
        payment_state: $payment_state,
        moderation_state: $moderation_state,
        switch: ChannelTestSwitches::None,
        expected: $expected,
    );
})->with([
    // Members and pseudo-members not subject to payment and moderation variation
    [MemberRoles::Owner, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::Admin, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::Moderator, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::ReadBot, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::ReadWriteBot, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::Guest, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    // Variation in Payment States
    [MemberRoles::Member, PaymentStates::Premium, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::GracePeriod, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::Trial, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::Free, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::Cancelled, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::Suspended, ModerationMemberStates::Active, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::Premium, ModerationMemberStates::Active, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::GracePeriod, ModerationMemberStates::Active, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::Trial, ModerationMemberStates::Active, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::Free, ModerationMemberStates::Active, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::Cancelled, ModerationMemberStates::Active, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::Suspended, ModerationMemberStates::Active, true],
    // Now test member types as exempt from payment on the moderation status variables
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::Limited, true],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::LimitAppealed, true],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::Suspended, false],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::SuspensionAppealed, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::Limited, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::LimitAppealed, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::Suspended, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::SuspensionAppealed, false],
]);

test('write permissions for blog channels', function (
    MemberRoles $role,
    PaymentStates $payment_state,
    ModerationMemberStates $moderation_state,
    bool $expected,
): void {
    channel_permissions(
        channel_type: ChannelTypes::Blog,
        permission_type: ChannelPermissionTypes::Write,
        role: $role,
        payment_state: $payment_state,
        moderation_state: $moderation_state,
        switch: ChannelTestSwitches::None,
        expected: $expected,
    );
})->with([
    // Members and pseudo-members not subject to payment and moderation variation
    [MemberRoles::Owner, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::Admin, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::Moderator, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::ReadBot, PaymentStates::Exempt, ModerationMemberStates::Active, false],
    [MemberRoles::ReadWriteBot, PaymentStates::Exempt, ModerationMemberStates::Active, false],
    [MemberRoles::Guest, PaymentStates::Exempt, ModerationMemberStates::Active, false],
    // Variation in Payment States
    [MemberRoles::Member, PaymentStates::Premium, ModerationMemberStates::Active, false],
    [MemberRoles::Member, PaymentStates::GracePeriod, ModerationMemberStates::Active, false],
    [MemberRoles::Member, PaymentStates::Trial, ModerationMemberStates::Active, false],
    [MemberRoles::Member, PaymentStates::Free, ModerationMemberStates::Active, false],
    [MemberRoles::Member, PaymentStates::Cancelled, ModerationMemberStates::Active, false],
    [MemberRoles::Member, PaymentStates::Suspended, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Premium, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::GracePeriod, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Trial, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Free, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Cancelled, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Suspended, ModerationMemberStates::Active, false],
    // Now test member types as exempt from payment on the moderation status variables
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::Limited, false],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::LimitAppealed, false],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::Suspended, false],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::SuspensionAppealed, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::Limited, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::LimitAppealed, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::Suspended, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::SuspensionAppealed, false],
]);

test('comment permissions for blog channels', function (
    MemberRoles $role,
    PaymentStates $payment_state,
    ModerationMemberStates $moderation_state,
    bool $expected,
): void {
    channel_permissions(
        channel_type: ChannelTypes::Blog,
        permission_type: ChannelPermissionTypes::Comment,
        role: $role,
        payment_state: $payment_state,
        moderation_state: $moderation_state,
        switch: ChannelTestSwitches::None,
        expected: $expected,
    );
})->with([
    // Members and pseudo-members not subject to payment and moderation variation
    [MemberRoles::Owner, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::Admin, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::Moderator, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::ReadBot, PaymentStates::Exempt, ModerationMemberStates::Active, false],
    [MemberRoles::ReadWriteBot, PaymentStates::Exempt, ModerationMemberStates::Active, true],
    [MemberRoles::Guest, PaymentStates::Exempt, ModerationMemberStates::Active, false],
    // Variation in Payment States
    [MemberRoles::Member, PaymentStates::Premium, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::GracePeriod, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::Trial, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::Free, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::Cancelled, ModerationMemberStates::Active, true],
    [MemberRoles::Member, PaymentStates::Suspended, ModerationMemberStates::Active, true],
    [MemberRoles::ReadOnlyMember, PaymentStates::Premium, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::GracePeriod, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Trial, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Free, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Cancelled, ModerationMemberStates::Active, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Suspended, ModerationMemberStates::Active, false],
    // Now test member types as exempt from payment on the moderation status variables
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::Limited, false],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::LimitAppealed, false],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::Suspended, false],
    [MemberRoles::Member, PaymentStates::Exempt, ModerationMemberStates::SuspensionAppealed, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::Limited, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::LimitAppealed, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::Suspended, false],
    [MemberRoles::ReadOnlyMember, PaymentStates::Exempt, ModerationMemberStates::SuspensionAppealed, false],
]);
