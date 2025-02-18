<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use Illuminate\Support\Facades\Context;

class Authenticated
{
    public function __invoke(): bool
    {
        return Context::get('active_member')->role !== MemberRoles::Guest;
    }
}
