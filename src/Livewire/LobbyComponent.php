<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use Livewire\Component;

class LobbyComponent extends Component
{
    public function render()
    {
        return view('hallway-flux::livewire.dashboard')->layout('hallway-flux::layouts.app');
    }
}
