<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Enums;

use ArtisanBuild\Hallway\Channels\Attributes\ChannelPermissions;
use ArtisanBuild\Hallway\Channels\Permissions\All;
use ArtisanBuild\Hallway\Channels\Permissions\Authenticated;
use ArtisanBuild\Hallway\Channels\Permissions\HasCommunityWritePrivileges;
use ArtisanBuild\Hallway\Channels\Permissions\InChannel;
use ArtisanBuild\Hallway\Channels\Permissions\IsChannelOwner;
use ArtisanBuild\Hallway\Channels\Permissions\IsNotSuspended;
use ArtisanBuild\Hallway\Channels\Permissions\IsOnAdminTeam;
use ArtisanBuild\Hallway\Channels\Permissions\IsOnModerationTeam;
use ArtisanBuild\Hallway\Channels\Permissions\IsPremiumMember;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\States\MemberState;

enum ChannelTypes: int
{
    // Community Channel Types
    #[ChannelPermissions(
        read: [Authenticated::class, IsNotSuspended::class],
        write: [Authenticated::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        invite: [Authenticated::class, IsOnModerationTeam::class, IsNotSuspended::class],
    )]
    case OpenFree = 0;
    #[ChannelPermissions(
        read: [Authenticated::class, IsPremiumMember::class, IsNotSuspended::class],
        write: [Authenticated::class, IsPremiumMember::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, IsPremiumMember::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case OpenPremium = 1;
    #[ChannelPermissions(
        read: [Authenticated::class, InChannel::class, IsNotSuspended::class],
        write: [Authenticated::class, InChannel::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, InChannel::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        invite: [Authenticated::class, IsOnModerationTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case PrivateFree = 2;
    #[ChannelPermissions(
        read: [Authenticated::class, InChannel::class, IsPremiumMember::class, IsNotSuspended::class],
        write: [Authenticated::class, InChannel::class, IsPremiumMember::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, InChannel::class, IsPremiumMember::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        invite: [Authenticated::class, IsOnModerationTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case PrivatePremium = 3;

    #[ChannelPermissions(
        read: [Authenticated::class, IsNotSuspended::class],
        write: [Authenticated::class, IsOnAdminTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, IsOnAdminTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case ReadOnlyFree = 4;
    #[ChannelPermissions(
        read: [Authenticated::class, IsPremiumMember::class, IsNotSuspended::class],
        write: [Authenticated::class, IsOnAdminTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, IsOnAdminTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case ReadOnlyPremium = 5;
    #[ChannelPermissions(
        read: [Authenticated::class, IsNotSuspended::class],
        write: [Authenticated::class, IsOnAdminTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case Announcements = 6;

    // Public Channels
    #[ChannelPermissions(
        read: [All::class, IsNotSuspended::class],
        write: [Authenticated::class, IsOnModerationTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case Blog = 7;
    #[ChannelPermissions(
        read: [All::class, IsNotSuspended::class],
        write: [Authenticated::class, IsOnModerationTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, IsOnAdminTeam::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case PublicAnnouncements = 8;

    // Member Channels
    #[ChannelPermissions(
        read: [Authenticated::class, InChannel::class, IsNotSuspended::class],
        write: [Authenticated::class, InChannel::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, InChannel::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        invite: [Authenticated::class, InChannel::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case Member = 11;
    #[ChannelPermissions(
        read: [Authenticated::class, InChannel::class, IsNotSuspended::class],
        write: [Authenticated::class, InChannel::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        comment: [Authenticated::class, InChannel::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
        invite: [Authenticated::class, IsChannelOwner::class, HasCommunityWritePrivileges::class, IsNotSuspended::class],
    )]
    case MemberPrivate = 12;

    public function isOpenChannel(): bool
    {
        return in_array($this, [
            self::OpenFree,
            self::OpenPremium,
            self::ReadOnlyFree,
            self::ReadOnlyPremium,
            self::Blog,
        ], true);
    }

    public function isCommunityChannel(): bool
    {
        return in_array($this, [
            self::OpenFree,
            self::OpenPremium,
            self::PrivateFree,
            self::PrivatePremium,
            self::ReadOnlyFree,
            self::ReadOnlyPremium,
            self::Blog,
        ], true);
    }

    public function isMemberChannel(): bool
    {
        return in_array($this, [
            self::Member,
            self::MemberPrivate,
        ], true);
    }

    public function isPublicChannel()
    {
        return in_array($this, [
            self::Blog,
            self::PublicAnnouncements,
        ], true);
    }

    public function authenticated(ChannelState $channel, MemberState $member): bool
    {
        return MemberRoles::Guest !== $member->role;
    }

    public function admin_team(ChannelState $channel, MemberState $member): bool
    {
        return in_array($member->role, [MemberRoles::Owner, MemberRoles::Admin], true);
    }

    public function editorial_team(ChannelState $channel, MemberState $member): bool
    {
        return in_array($member->role, [MemberRoles::Owner, MemberRoles::Admin, MemberRoles::Moderator], true);
    }
}
