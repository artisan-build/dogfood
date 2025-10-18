<?php

declare(strict_types=1);

namespace ArtisanBuild\FluxThemes\Enums;

enum NavbarItemTypes: string
{
    case BladeComponent = 'blade-component';
    case LivewireComponent = 'livewire-component';
    case NavbarItem = 'navbar-item';
}
