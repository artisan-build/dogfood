<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use Illuminate\Support\Facades\Context;

class IsOnAdminTeam
{
    public function __invoke(): bool
    {
        return in_array(Context::get('active_member')->role, [
            MemberRoles::Owner,
            MemberRoles::Admin,
        ], true);
    }
}
