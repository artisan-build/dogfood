<?php

declare(strict_types=1);

namespace ArtisanBuild\FluxThemes\Livewire;

use ArtisanBuild\FluxThemes\Contracts\LoadsHeaderLeftNavbarItems;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Component;

class HeaderLeftNavbarComponent extends Component
{
    public function render(): Application|\Illuminate\Contracts\View\View|Factory|View|null
    {
        return view('flux-themes::livewire.header-left-sidebar', ['items' => resolve(LoadsHeaderLeftNavbarItems::class)()]);
    }
}
