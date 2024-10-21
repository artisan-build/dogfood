<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Enums;

enum MemberChannelRoles: int
{
    // Special Human Channel Members
    case Admin = 0;
    case Moderator = 1;

    // Human Channel Members
    case Member = 11;
    case ReadOnly = 12;
    case Invited = 13;

    // Bot Channel Members
    case ModeratorBot = 21;
    case ReadWriteBot = 22;
    case ReadBot = 23;
}
