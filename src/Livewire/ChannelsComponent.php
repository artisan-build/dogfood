<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Channels\Events\ChannelsRequested;
use Illuminate\Support\Collection;
use Livewire\Component;

class ChannelsComponent extends Component
{
    public Collection $channels;

    public function mount(): void
    {
        $this->channels = ChannelsRequested::commit();
    }

    public function render()
    {
        return view('hallway-flux::livewire.channels')->layout('hallway-flux::layouts.app');
    }
}
