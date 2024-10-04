<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Jetstream\States\UserState;
use ArtisanBuild\Jetstream\Traits\AuthorizesBasedOnUserRole;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserAddedToChannel extends Event
{
    use AuthorizesBasedOnUserRole;

    #[StateId(UserState::class)]
    public int $user_id;

    #[StateId(ChannelState::class)]
    public int $channel_id;

    // TODO: I'm not sure whether this is a good idea or not. It might be better to just let it fly
    // and de-duplicate because we are checking two conditions and any out-of-sync data could land us
    // in a stuck position.
    public function validate(UserState $user_state, ChannelState $channel_state): bool
    {
        return ! in_array($this->channel_id, $user_state->channel_ids, true)
            && ! in_array($this->user_id, $channel_state->user_ids, true);
    }

    public function applyToUserState(UserState $state): void
    {
        $state->channel_ids[] = $this->channel_id;
    }

    public function applyToChannelState(ChannelState $state): void
    {
        $state->user_ids[] = $this->user_id;
    }

    public function handle(): void
    {
        // I play the hand that was dealt, I got a deck full of aces. I gave birth to your style, I need a check for my labor - Lil Wayne
    }
}
