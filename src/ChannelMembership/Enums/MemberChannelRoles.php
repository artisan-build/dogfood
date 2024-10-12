<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Enums;

enum MemberChannelRoles: int
{
    case Admin = 0;
    case Moderator = 1;
    case Member = 2;
    case ReadOnly = 3;
    case ModeratorBot = 4;
    case ReadWriteBot = 5;
    case ReadBot = 6;
}
