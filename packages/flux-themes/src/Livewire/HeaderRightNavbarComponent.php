<?php

namespace ArtisanBuild\FluxThemes\Livewire;

use ArtisanBuild\FluxThemes\Contracts\LoadsHeaderRightNavbarItems;
use Livewire\Component;

class HeaderRightNavbarComponent extends Component
{
    public function render()
    {
        return view('flux-themes::livewire.header-right-navbar', ['items' => app(LoadsHeaderRightNavbarItems::class)()]);
    }
}
