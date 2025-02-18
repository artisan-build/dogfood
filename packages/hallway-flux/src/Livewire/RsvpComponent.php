<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Component;

class RsvpComponent extends Component
{
    public GatheringState $gathering;

    public function render(): Application|\Illuminate\Contracts\View\View|Factory|View|null
    {
        return view('hallway-flux::livewire.rsvp');
    }
}
