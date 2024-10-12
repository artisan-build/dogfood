<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Channels\Models\Channel;
use Livewire\Component;

class ChannelComponent extends Component
{
    public Channel $channel;

    public function render()
    {
        return view('hallway-flux::livewire.channel')->layout('hallway-flux::layouts.app');
    }
}
