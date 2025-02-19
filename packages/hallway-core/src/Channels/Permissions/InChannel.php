<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use Illuminate\Support\Facades\Context;

class InChannel
{
    public function __invoke()
    {
        $member = Context::get('active_member');

        if ($member->in_channel) {
            return true;
        }

        return $member->inChannel();
    }
}
