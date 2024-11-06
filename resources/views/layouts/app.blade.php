@php
    use App\Enums\UsersFixture;
    use ArtisanBuild\Hallway\Channels\Models\Channel;
    use ArtisanBuild\Hallway\Members\Models\Member;
    use ArtisanBuild\HallwayFlux\Livewire\Layout\MyChannelsComponent;
    use ArtisanBuild\HallwayFlux\Livewire\Layout\DetectMemberTimezone;use ArtisanBuild\HallwayFlux\Livewire\Layout\LogoutButton;
@endphp
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

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.7.0/github-markdown.min.css"
          integrity="sha512-RXrQNShK831yZVcMWsLosdpsHddeG5xP7zMmlDu/OLQdfx24Z9pO1KiFZ1eZrMqY8P9hYgknwU/O6GxR2Fc0Gw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

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
                               href="{{ route(config('hallway-flux.route-name-prefix') .'calendar', ['range' => now()->format('Y-m')]) }}">
                Calendar
            </flux:navlist.item>

            @livewire(MyChannelsComponent::class)
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
                <flux:profile avatar="{{ Context::get('active_member')?->profile_picture_url }}"
                              name="{{ Context::get('active_member')?->display_name }}"/>


                <flux:menu>
                    <flux:menu.radio.group>
                        @foreach (\Illuminate\Support\Facades\Auth::user()?->hallway_members as $member)
                            <flux:menu.radio
                                :checked="$member->id === Context::get('active_member')->id">{{ $member->handle }}</flux:menu.radio>
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
                <flux:profile avatar="{{ Context::get('member')?->profile_picture_url }}"/>

                <flux:menu>
                    <flux:menu.radio.group>
                        @foreach (\Illuminate\Support\Facades\Auth::user()?->hallway_members as $member)
                            <flux:menu.radio
                                :checked="$member->id === Context::get('active_member')->id">{{ $member->handle }}</flux:menu.radio>
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
                @env('local')
                    <flux:modal.trigger name="local-logins">
                        <flux:navbar.item>[Development Logins]</flux:navbar.item>
                    </flux:modal.trigger>
                @endenv
                <flux:navbar.item wire:navigate href="{{ route('register') }}">Register</flux:navbar.item>
                <flux:navbar.item wire:navigate href="{{ route('login') }}">Log In</flux:navbar.item>
            @endif
        </flux:navbar>
    @endif
</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>

@env('local')
    <flux:modal name="local-logins" variant="bare"
                class="w-auto h-fit bg-white dark:bg-zinc-800 border border-transparent dark:border-zinc-700">
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-slate-700">
                        <thead>
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-slate-200 sm:pl-3">
                                Name
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-slate-200">
                                Role
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-slate-200">
                                Email
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-slate-200">
                                Payment
                                Status
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-slate-200">
                                Moderation
                                Status
                            </th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                        @foreach (collect(UsersFixture::cases())->sortBy('name') as $case)
                            <tr class="even:bg-gray-50 dark:even:bg-slate-900">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-slate-200 sm:pl-3">
                                    {{ $case->data($case, 'name') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $case->data($case, 'role')->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $case->data($case, 'email') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $case->data($case, 'payment_status')->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $case->data($case, 'moderation_status')->name }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                    <a href="{{ route('local-log-in', ['user' => (string)$case->value]) }}"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-slate-300 dark:hover:text-slate-100">Log
                                        In<span
                                            class="sr-only">, Log In As {{ $case->data($case, 'name') }}</span></a>
                                </td>
                            </tr>
                        @endforeach

                        <!-- More people... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </flux:modal>
@endenv

@stack('modals')

@persist('toast')
<flux:toast position="top right"/>
@endpersist


@livewireScripts
@fluxScripts
<livewire:detect-member-timezone/>
</body>
</html>
