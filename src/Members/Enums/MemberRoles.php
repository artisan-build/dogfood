<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Enums;

use ArtisanBuild\FatEnums\Attributes\VerbsCan;
use Illuminate\Support\Str;

enum MemberRoles: int
{
    case Owner = 0;
    case Admin = 1;

    #[VerbsCan([

    ])]
    case Moderator = 2;

    case Member = 3;

    case ReadOnlyMember = 4;

    // Bot Roles
    case ModeratorBot = 5;
    case ReadWriteBot = 6;
    case ReadBot = 7;

    public function isBot(): bool
    {
        // TODO: Is this faster than a match statement or in_array or something else?
        return Str::endsWith('Bot', $this->name);
    }

    public function getColor(): string
    {
        /**
         * Available Flux Badge Colors:
         * zinc, red, orange, amber, yellow, lime,
         * green, emerald, teal, cyan, sky, blue,
         * indigo, violet, purple, fuchsia, pink, rose
         */

        return match ($this) {
            self::Owner         => 'red',
            self::Admin         => 'yellow',
            self::Moderator     => 'lime',
            self::Member        => 'sky',
            self::ReadOnlyMember => 'indigo',
            self::ModeratorBot  => 'violet',
            self::ReadWriteBot  => 'purple',
            self::ReadBot       => 'rose',
        };
    }
}
