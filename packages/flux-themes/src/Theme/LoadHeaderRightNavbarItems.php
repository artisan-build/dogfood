<?php

namespace ArtisanBuild\FluxThemes\Theme;

use ArtisanBuild\FluxThemes\Contracts\LoadsHeaderRightNavbarItems;
use ArtisanBuild\FluxThemes\Enums\NavbarItemTypes;
use ArtisanBuild\Verbstream\Http\Livewire\UserHeaderMenuComponent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class LoadHeaderRightNavbarItems implements LoadsHeaderRightNavbarItems
{
    public function __invoke(): array
    {
        return auth()->check() ? $this->user() : $this->guest();
    }

    protected function user(): array
    {
        $items = [];

        $items[] = [
            'type' => NavbarItemTypes::BladeComponent->value,
            'component' => 'till::flux-navbar-item-pricing-if-not-subscribed',
            'params' => [],
        ];

        if ($this->hasDarkMode()) {

            $items[] = [
                'type' => NavbarItemTypes::BladeComponent->value,
                'component' => 'flux-themes::dark-mode-selector-compact',
                'params' => [],
            ];
        }

        if (class_exists(UserHeaderMenuComponent::class)) {
            $items[] = [
                'type' => NavbarItemTypes::LivewireComponent->value,
                'component' => UserHeaderMenuComponent::class,
                'params' => [],
            ];
        }

        return $items;
    }

    protected function guest(): array
    {
        $items = [];

        if (Route::has('login')) {
            $items[] = [
                'type' => NavbarItemTypes::NavbarItem->value,
                'href' => route('login'),
                'text' => 'Log In',
                'icon' => null,
                'target' => null,
            ];
        }

        if (Route::has('register')) {
            $items[] = [
                'type' => NavbarItemTypes::NavbarItem->value,
                'href' => route('register'),
                'text' => 'Register',
                'icon' => null,
                'target' => null,
            ];
        }

        if ($this->hasDarkMode()) {
            $items[] = [
                'type' => NavbarItemTypes::BladeComponent->value,
                'component' => 'flux-themes::dark-mode-selector-compact',
                'params' => [],
            ];
        }

        return $items;
    }

    protected function hasDarkMode(): bool
    {
        return str_contains(File::get(base_path('tailwind.config.js')), "darkMode: 'selector',");
    }
}
