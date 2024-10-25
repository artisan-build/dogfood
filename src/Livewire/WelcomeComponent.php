<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use Livewire\Component;

class WelcomeComponent extends Component
{
    public function render()
    {
        return view('welcome')->layout('hallway-flux::layouts.app');
    }
}
