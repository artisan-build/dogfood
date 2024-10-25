<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire\Layout;

use ArtisanBuild\Hallway\Channels\Events\ChannelsRequested;
use Livewire\Component;

class ChannelsComponent extends Component
{
    public $channels;

    public function mount(): void
    {
        $this->channels = ChannelsRequested::commit();
    }
    public function render()
    {
        return view('hallway-flux::livewire.layout.channels');
    }

}
