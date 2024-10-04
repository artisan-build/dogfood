@php use ArtisanBuild\Hallway\Channels\Models\Channel; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    @fluxStyles
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <flux:brand href="#" logo="https://artisan.build/img/logo.png" name="Artisan Build" class="px-2 dark:hidden"/>
    <flux:brand href="#" logo="https://artisan.build/img/logo.png" name="Artisan Build"
                class="px-2 hidden dark:flex"/>

    <flux:input as="button" variant="filled" placeholder="Search..." icon="magnifying-glass"/>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" href="#" current>Lobby</flux:navlist.item>
        <flux:navlist.item icon="calendar" href="#">Calendar</flux:navlist.item>

        <flux:navlist.group expandable heading="Channels" class="hidden lg:grid">
            @foreach (Channel::all() as $channel)
                <flux:navlist.item href="#" icon="lock-open">{{ $channel->name }}</flux:navlist.item>
            @endforeach
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer/>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="cog-6-tooth" href="#">Settings</flux:navlist.item>
        <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
    </flux:navlist>

    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:profile avatar="{{ Auth::user()?->profile_photo_url }}" name="{{ Auth::user()?->name }}"/>

        <flux:menu>
            <flux:menu.radio.group>
                <flux:menu.radio checked>{{ Auth::user()?->name }}</flux:menu.radio>
            </flux:menu.radio.group>

            <flux:menu.separator/>

            <form method="post" action="{{ route('logout') }}">
                @csrf
                <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('logout') }}"
                                @click.prevent="$root.submit();">{{ __('Log Out') }}</flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>

<flux:header class="!block bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:navbar class="lg:hidden w-full">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

        <flux:spacer/>

        <flux:dropdown position="top" align="start">
            <flux:profile avatar="{{ Auth::user()?->profile_photo_url }}"/>

            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>{{ \Illuminate\Support\Facades\Auth::user()?->name }}</flux:menu.radio>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();">{{ __('Log Out') }}</flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:navbar>

    <flux:navbar scrollable>
        <flux:navbar.item href="{{ route(config('hallway-flux.route-name-prefix') .'dashboard') }}">Dashboard
        </flux:navbar.item>
        <flux:navbar.item badge="{{ ArtisanBuild\Hallway\Members\Models\Member::count() }}" href="/members">Members
        </flux:navbar.item>
        <flux:navbar.item href="#">Mentions</flux:navbar.item>
        <flux:navbar.item href="#">Bookmarks</flux:navbar.item>
    </flux:navbar>
</flux:header>

<flux:main>
    Hello
    {{ $slot }}
</flux:main>


@stack('modals')

@livewireScripts
@fluxScripts
</body>
</html>
