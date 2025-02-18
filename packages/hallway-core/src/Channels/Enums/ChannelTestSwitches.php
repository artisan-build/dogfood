<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Enums;

enum ChannelTestSwitches
{
    case None;
    case InChannel;
    case OwnsChannel;
}
