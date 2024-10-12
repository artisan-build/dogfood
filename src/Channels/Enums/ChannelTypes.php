<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Enums;

enum ChannelTypes: int
{
    case OpenFree = 0;
    case OpenPremium = 1;
    case PrivateFree = 2;
    case PrivatePremium = 3;
    case MemberInvitationOnly = 4;
    case MemberAddByMention = 5;
}
