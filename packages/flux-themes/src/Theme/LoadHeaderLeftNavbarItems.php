<?php

namespace ArtisanBuild\FluxThemes\Theme;

use ArtisanBuild\FluxThemes\Contracts\LoadsHeaderRightNavbarItems;
use ArtisanBuild\FluxThemes\Enums\NavbarItemTypes;

class LoadHeaderLeftNavbarItems implements LoadsHeaderRightNavbarItems
{
    public function __invoke(): array
    {
        return auth()->check() ? $this->user() : $this->guest();
    }

    protected function user(): array
    {
        return [
            [
                'type' => NavbarItemTypes::NavbarItem,
                'href' => route('login'),
                'text' => 'Blog',
                'icon' => null,
                'target' => null,
            ],
            [
                'type' => NavbarItemTypes::NavbarItem,
                'href' => route('register'),
                'text' => 'Features',
                'icon' => null,
                'target' => null,
            ],
            [
                'type' => NavbarItemTypes::NavbarItem,
                'href' => route('register'),
                'text' => 'Pricing',
                'icon' => null,
                'target' => null,
            ],
        ];
    }

    protected function guest(): array
    {
        return [
            [
                'type' => NavbarItemTypes::NavbarItem->value,
                'href' => '#',
                'text' => 'Blog',
                'icon' => null,
                'target' => null,
            ],
            [
                'type' => NavbarItemTypes::NavbarItem->value,
                'href' => '#',
                'text' => 'Pricing',
                'icon' => null,
                'target' => null,
            ],
        ];
    }
}
