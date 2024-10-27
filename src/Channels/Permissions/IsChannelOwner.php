<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use Illuminate\Support\Facades\Context;

class IsChannelOwner
{
    public function __invoke(): bool
    {
        $channel = Context::get('channel');
        $member = Context::get('active_member');

        return null !== $channel->owner_id && $channel->owner_id === $member->id;
    }
}
