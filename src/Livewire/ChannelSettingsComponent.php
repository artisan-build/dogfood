<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Channels\Models\Channel;
use Livewire\Component;

class ChannelSettingsComponent extends Component
{
    public Channel $channel;

    public function render()
    {
        return view('hallway-flux::livewire.channel_settings')->layout('hallway-flux::layouts.app');
    }
}
