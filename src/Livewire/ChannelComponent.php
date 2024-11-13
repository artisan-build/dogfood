<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use Illuminate\Support\Collection;
use Livewire\Component;

class ChannelComponent extends Component
{
    public ChannelState $channel;

    public Collection $threads;

    public $listeners = [
        'saved' => 'refreshChannel',
    ];

    public function mount(): void
    {
        $this->threads = $this->channel->messages();
    }

    public function refreshChannel(): void
    {
        $this->threads = $this->channel->messages();
        $this->dispatch('reload');
    }



    public function render()
    {
        return view('hallway-flux::livewire.channel')->layout('hallway-flux::layouts.app');
    }
}
