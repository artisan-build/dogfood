<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use Livewire\Component;

class CalendarComponent extends Component
{
    public function render()
    {
        return view('hallway-flux::livewire.calendar')->layout('hallway-flux::layouts.app');
    }
}
