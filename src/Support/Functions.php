<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Support;

use ArtisanBuild\Hallway\Members\States\MemberState;
use Illuminate\Support\Facades\Context;

class Functions
{
    public static function can(string $event, ?MemberState $member = null)
    {
        $member ??= Context::get('active_member');
        return $member->can($event);
    }

}
