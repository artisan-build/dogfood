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

    // Member Channels
    case Member = 11;
}
