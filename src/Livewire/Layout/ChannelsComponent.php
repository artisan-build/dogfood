<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire\Layout;

use ArtisanBuild\Hallway\Channels\Models\Channel;
use Livewire\Component;

class ChannelsComponent extends Component
{
    public $channels;

    public function mount(): void
    {
        $this->channels = Channel::all();
    }
    public function render()
    {
        return view('hallway-flux::livewire.layout.channels');
    }

}
