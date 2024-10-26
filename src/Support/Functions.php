<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Support;

use ArtisanBuild\Hallway\Members\States\MemberState;

class Functions
{
    public static function can(string $event, MemberState $member)
    {
        return $member->can($event);
    }
}
