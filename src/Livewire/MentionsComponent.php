<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use Livewire\Component;

class MentionsComponent extends Component
{
    public function render()
    {
        return view('hallway-flux::livewire.mentions')->layout('hallway-flux::layouts.app');
    }
}
