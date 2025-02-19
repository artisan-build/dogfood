<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\ChannelMembership\Events;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use Illuminate\Support\Arr;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class MemberLeftChannel extends Event
{
    use AuthorizesBasedOnMemberState;

    #[StateId(MemberState::class)]
    public int $member_id;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    public function applyToMemberState(MemberState $member): void
    {
        $member->muted_channel_ids = Arr::addUniqueToList($member->muted_channel_ids, $this->channel_id);
    }

    public function applyToChannelState(ChannelState $channel): void
    {
        $channel->member_ids = Arr::removeFromList($channel->member_ids, $this->member_id);
    }
}
