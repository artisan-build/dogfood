<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Enums;

enum ChannelTypes: string
{
    case OpenFree = 'OpenFree';
    case OpenPaid = 'OpenPaid';
    case PrivateFree = 'PrivateFree';
    case PrivatePaid = 'PrivatePaid';
}
