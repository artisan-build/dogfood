<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberRole;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class ChannelNameChanged extends Event
{
    use AuthorizesBasedOnMemberRole;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    public string $name;

    public function apply(ChannelState $state): void
    {
        $state->name = $this->name;
    }

    public function handle(): void
    {
        // You think you're calling shots, you got the wrong number. I love Benjamin Franklin more than his own mother. - Lil Wayne
    }
}
