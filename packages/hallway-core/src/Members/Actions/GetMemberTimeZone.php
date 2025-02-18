<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Actions;

/*
 * This class only exists to return the member's time zone for event form purposes because we can't
 * use PHP inside of attributes. We pass this FQCN to the default value of the timezone when needed and
 * the form builder invokes this class to get the value.
 */

use Illuminate\Support\Facades\Context;

class GetMemberTimeZone
{
    public function __invoke()
    {
        return Context::get('active_member')->timezone;
    }
}
