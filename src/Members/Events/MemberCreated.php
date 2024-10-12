<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Events;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Jetstream\States\UserState;
use Illuminate\Support\Collection;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class MemberCreated extends Event
{
    #[StateId(MemberState::class)]
    public int $member_id;

    #[StateId(UserState::class)]
    public int $user_id;

    public string $handle;


    public array $channel_ids = [];

    public function applyToMemberState(MemberState $state): void
    {
        $state->user_id = $this->user_id;
        $state->handle = $this->handle;
    }

    public function applyToUserState(UserState $state): void
    {
        $state->member_ids[] = $this->member_id;
    }


    public function channels(): Collection
    {
        return collect($this->channel_ids)->map(fn(int $id) => ChannelState::load($id));
    }

}
