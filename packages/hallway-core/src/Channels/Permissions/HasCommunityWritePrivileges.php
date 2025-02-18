<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use Illuminate\Support\Facades\Context;

class HasCommunityWritePrivileges
{
    public function __invoke(): bool
    {
        $member = Context::get('active_member');

        return $member->moderation_state->hasCommunityWritePrivileges()
            && $member->role->hasCommunityWritePrivileges();

    }
}
