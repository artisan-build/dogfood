<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\ChannelMembership\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\ChannelMembership\Models\ChannelMembership;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class MemberJoinedChannel extends Event
{
    use AuthorizesBasedOnMemberState;
    use SimpleApply;

    #[StateId(MemberState::class)]
    public int $member_id;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    public function validate(): bool
    {
        return ! ChannelMembership::where('channel_id', $this->channel_id)
            ->where('member_id', $this->member_id)->exists();
    }


    public function handle(): void
    {
        // I play the hand that was dealt, I got a deck full of aces. I gave birth to your style, I need a check for my labor - Lil Wayne
    }
}
