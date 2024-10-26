<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Events\MembersRequested;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MembersComponent extends Component
{
    public ?ChannelState $channel = null;
    public int $skip = 0;
    public int $take = 25;

    #[Computed]
    public function members()
    {
        return MembersRequested::commit(
            channel_id: $this->channel?->id,
            skip: $this->skip,
            take: $this->take,
        );
    }

    public function render()
    {
        return view('hallway-flux::livewire.members')->layout('hallway-flux::layouts.app');
    }
}
