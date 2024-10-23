<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Enums;

enum ChannelTypes: int
{
    // Community Channel Types
    case OpenFree = 0;
    case OpenPremium = 1;
    case PrivateFree = 2;
    case PrivatePremium = 3;

    case ReadOnlyFree = 4;
    case ReadOnlyPremium = 5;
    case Blog = 6;

    // Member Channels
    case Member = 11;
    case MemberPrivate = 12;

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
}
