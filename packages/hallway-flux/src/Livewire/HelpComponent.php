<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use Livewire\Component;

class HelpComponent extends Component
{
    public function render()
    {
        return view('hallway-flux::livewire.help')->layout('hallway-flux::layouts.app');
    }
}
