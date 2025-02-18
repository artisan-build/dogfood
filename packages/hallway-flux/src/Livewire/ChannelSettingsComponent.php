<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use Livewire\Component;

class ChannelSettingsComponent extends Component
{
    public ChannelState $channel;

    public function render()
    {
        return view('hallway-flux::livewire.channel_settings')->layout('hallway-flux::layouts.app');
    }
}
