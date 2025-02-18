@php use ArtisanBuild\FluxThemes\Enums\NavbarItemTypes; @endphp
@props(['item'])
@if ($item['type'] === NavbarItemTypes::BladeComponent->value)
    <x-dynamic-component :component="$item['component']"/>
@endif
@if ($item['type'] === NavbarItemTypes::NavbarItem->value)
    <x-flux::navbar.item :href="$item['href']">{{ $item['text'] }}</x-flux::navbar.item>
@endif
@if ($item['type'] === NavbarItemTypes::LivewireComponent->value)
    @livewire($item['component'], $item['params'])
@endif
