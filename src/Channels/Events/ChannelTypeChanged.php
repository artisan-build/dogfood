<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Jetstream\Traits\AuthorizesBasedOnUserRole;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class ChannelTypeChanged extends Event
{
    use AuthorizesBasedOnUserRole;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    public ChannelTypes $type;

    public function apply(ChannelState $state): void
    {
        $state->type = $this->type;
    }

    public function handle(): void
    {
        // And I swear to everything, when I leave this Earth, it's gonna be on both feet, never knees in the dirt. - Lil Wayne
    }
}
