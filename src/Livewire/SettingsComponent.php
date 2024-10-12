<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use Livewire\Component;

class SettingsComponent extends Component
{
    public function render()
    {
        return view('hallway-flux::livewire.settings')->layout('hallway-flux::layouts.app');
    }
}
