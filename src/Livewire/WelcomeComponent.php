<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Channels\Models\Channel;
use Livewire\Component;

class WelcomeComponent extends Component
{
    public Channel $channel;

    public function render()
    {
        return view('welcome')->layout('hallway-flux::layouts.app');
    }
}
