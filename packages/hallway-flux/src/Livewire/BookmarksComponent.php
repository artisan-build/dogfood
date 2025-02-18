<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use Livewire\Component;

class BookmarksComponent extends Component
{
    public function render()
    {
        return view('hallway-flux::livewire.bookmarks')->layout('hallway-flux::layouts.app');
    }
}
