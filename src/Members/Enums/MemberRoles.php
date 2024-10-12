<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Enums;

use Illuminate\Support\Str;

enum MemberRoles: int
{
    // Human Roles

    case Admin = 0;
    case Moderator = 1;
    case Member = 2;

    // Bot Roles
    case ModeratorBot = 3;
    case ReadWriteBot = 4;
    case ReadBot = 5;

    public function isBot(): bool
    {
        // TODO: Is this faster than a match statement or in_array or something else?
        return Str::endsWith('Bot', $this->name);
    }

}
