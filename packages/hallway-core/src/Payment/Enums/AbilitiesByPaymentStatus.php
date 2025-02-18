<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Payment\Enums;

enum AbilitiesByPaymentStatus
{
    case ReadFreeChannels;
    case PostInFreeChannels;
    case ReadPremiumChannels;
    case PostInPremiumChannels;

}
