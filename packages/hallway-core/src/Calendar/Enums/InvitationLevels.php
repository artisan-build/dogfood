<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Enums;

enum InvitationLevels: int
{
    case Public = 0;
    case Free = 11;
    case Premium = 21;
}
