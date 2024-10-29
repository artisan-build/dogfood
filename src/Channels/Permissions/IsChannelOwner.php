<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\States\MemberState;
use Illuminate\Support\Facades\Context;

class IsChannelOwner
{
    public function __invoke(): bool
    {
        $channel = Context::get('channel');
        $member = Context::get('active_member');
        assert($channel instanceof ChannelState);
        assert($member instanceof MemberState);

        // This property is marked as deprecated because it is only used in testing to avoid database access in 1000+ assertions
        if ($member->owns_channel) {
            return true;
        }

        return null !== $channel->owner_id && $channel->owner_id === $member->id;
    }
}
