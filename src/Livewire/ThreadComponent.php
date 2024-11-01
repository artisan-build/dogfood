<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Messages\States\MessageState;
use Livewire\Component;

class ThreadComponent extends Component
{
    public MessageState $message;

    public function render()
    {
        return view('hallway-flux::livewire.thread')->layout('hallway-flux::layouts.app');
    }
}
