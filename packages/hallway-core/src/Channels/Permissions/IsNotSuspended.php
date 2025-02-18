<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use ArtisanBuild\Hallway\Moderation\Enums\ModerationMemberStates;
use Illuminate\Support\Facades\Context;

class IsNotSuspended
{
    public function __invoke(): bool
    {
        return ! in_array(Context::get('active_member')->moderation_state, [
            ModerationMemberStates::Suspended,
            ModerationMemberStates::SuspensionAppealed,
        ], true);
    }
}
