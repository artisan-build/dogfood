@php use ArtisanBuild\Hallway\Channels\Models\Channel;use ArtisanBuild\Hallway\Members\Models\Member;use ArtisanBuild\HallwayFlux\Livewire\Layout\ChannelsComponent;use ArtisanBuild\HallwayFlux\Livewire\Layout\LogoutButton; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Powered by Hallway.fm') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    @fluxStyles
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
@auth
    <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

        <flux:brand wire:navigate href="{{ route(config('hallway-flux.route-name-prefix') .'lobby') }}"
                    logo="{{ config('hallway-flux.community.logo_light') }}"
                    name="{{ config('hallway-flux.community.name') }}" class="px-2 dark:hidden"/>
        <flux:brand wire:navigate href="{{ route(config('hallway-flux.route-name-prefix') .'lobby') }}"
                    logo="{{ config('hallway-flux.community.logo_light') }}"
                    name="{{ config('hallway-flux.community.name') }}"
                    class="px-2 hidden dark:flex"/>

        <flux:modal.trigger name="search-modal">
            <flux:input as="button" variant="filled" placeholder="Search..." icon="magnifying-glass"/>
        </flux:modal.trigger>

        <flux:modal name="search-modal" class="space-y-6">
            <flux:input label="Search members and messages" placeholder="Search" icon="magnifying-glass"/>
        </flux:modal>


        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" wire:navigate
                               href="{{ route(config('hallway-flux.route-name-prefix') .'lobby') }}">Lobby
            </flux:navlist.item>
            <flux:navlist.item icon="calendar" wire:navigate
                               href="{{ route(config('hallway-flux.route-name-prefix') .'calendar', ['range' => now()->format('Y-m')]) }}">Calendar
            </flux:navlist.item>

            @livewire(ChannelsComponent::class)
        </flux:navlist>

        <flux:spacer/>

        <flux:navlist variant="outline">
            <flux:navlist.item icon="cog-6-tooth" wire:navigate
                               href="{{ route(config('hallway-flux.route-name-prefix') . 'settings') }}">Settings
            </flux:navlist.item>
            <flux:navlist.item icon="information-circle" wire:navigate
                               href="{{ route(config('hallway-flux.route-name-prefix') . 'help') }}">Help
            </flux:navlist.item>
        </flux:navlist>
        @auth
            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:profile avatar="{{ Auth::user()?->profile_photo_url }}" name="{{ Auth::user()?->name }}"/>


                <flux:menu>
                    <flux:menu.radio.group>
                        @foreach (\Illuminate\Support\Facades\Auth::user()?->hallway_members as $member)
                            <flux:menu.radio checked>{{ $member->handle }}</flux:menu.radio>
                        @endforeach
                    </flux:menu.radio.group>

                    <flux:separator class="my-4" text="{{ \Illuminate\Support\Facades\Auth::user()?->name }}"/>

                    <livewire:logout-button/>
                </flux:menu>

            </flux:dropdown>
        @endauth
        <flux:navlist variant="outline">
            <flux:navlist.item icon="building-storefront" target="_blank" href="https://hallway.fm">Powered by
                Hallway.fm
            </flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>
@endauth

<flux:header class="!block bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:navbar class="lg:hidden w-full">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

        <flux:spacer/>

        @auth
            <flux:dropdown position="top" align="start">
                <flux:profile avatar="{{ Auth::user()?->profile_photo_url }}"/>

                <flux:menu>
                    <flux:menu.radio.group>
                        @foreach (\Illuminate\Support\Facades\Auth::user()?->hallway_members as $member)
                            <flux:menu.radio checked>{{ $member->handle }}</flux:menu.radio>
                        @endforeach
                    </flux:menu.radio.group>

                    <flux:menu.separator text="{{ \Illuminate\Support\Facades\Auth::user()?->name }}"/>

                    @livewire(LogoutButton::class)
                </flux:menu>
            </flux:dropdown>
        @endauth
    </flux:navbar>

    @if (request()->route()->hasParameter('channel'))
        <flux:navbar scrollable>
            <flux:navbar.item wire:navigate
                              href="{{ route(config('hallway-flux.route-name-prefix') .'channel', ['channel' => request()->route()->channel]) }}">{{ request()->route()->channel->name }}
            </flux:navbar.item>
            <flux:navbar.item badge="{{ Member::count() }}" wire:navigate
                              href="{{ route(config('hallway-flux.route-name-prefix') .'channel_members', ['channel' => request()->route()->channel]) }}">
                Members
            </flux:navbar.item>
            <flux:navbar.item wire:navigate
                              href="{{ route(config('hallway-flux.route-name-prefix') .'channel_settings', ['channel' => request()->route()->channel]) }}">
                Settings
            </flux:navbar.item>

        </flux:navbar>
    @else
        <flux:navbar class="{{ \Illuminate\Support\Facades\Auth::guest() ? 'justify-end' : '' }}" scrollable>
            @auth
                <flux:navbar.item wire:navigate href="{{ route(config('hallway-flux.route-name-prefix') .'lobby') }}">
                    Lobby
                </flux:navbar.item>
                <flux:navbar.item badge="{{ Member::count() }}" wire:navigate
                                  href="{{ route(config('hallway-flux.route-name-prefix') . 'members') }}">Members
                </flux:navbar.item>
                <flux:navbar.item wire:navigate
                                  href="{{ route(config('hallway-flux.route-name-prefix') .'mentions') }}">
                    Mentions
                </flux:navbar.item>
                <flux:navbar.item wire:navigate
                                  href="{{ route(config('hallway-flux.route-name-prefix') .'bookmarks') }}">
                    Bookmarks
                </flux:navbar.item>
            @else
                <flux:navbar.item wire:navigate href="{{ route('register') }}">Register</flux:navbar.item>
                <flux:navbar.item wire:navigate href="{{ route('login') }}">Log In</flux:navbar.item>
            @endif
        </flux:navbar>
    @endif
</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>


@stack('modals')

@livewireScripts
@fluxScripts
</body>
</html>
