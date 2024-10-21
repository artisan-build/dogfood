<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\ChannelMembership\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class MemberRemovedFromChannel extends Event
{
    use AuthorizesBasedOnMemberState;
    use SimpleApply;

    #[StateId(MemberState::class)]
    public int $member_id;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    public function handle(): void
    {
        // Most of yall don't get the picture unless the flash is on. - Lil Wayne
    }
}
