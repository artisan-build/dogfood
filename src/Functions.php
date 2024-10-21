<?php

declare(strict_types=1);

use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Support\Functions;

if ( ! function_exists('hallway')) {
    function hallway(): void {}
}

if ( ! function_exists('hallway_can')) {
    function hallway_can(string $event, ?MemberState $member = null)
    {
        return Functions::can($event, $member);
    }
}
