<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Jetstream\Traits\AuthorizesBasedOnUserRole;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class ChannelCreated extends Event
{
    use AuthorizesBasedOnUserRole;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    public string $type;
    public string $name;



    public function apply(ChannelState $state): void
    {
        $state->name = $this->name;
        $state->type = ChannelTypes::tryFrom($this->type);
    }

    public function handle(): void
    {
        // I play the hand that was dealt, I got a deck full of aces. I gave birth to your style, I need a check for my labor - Lil Wayne
    }
}
