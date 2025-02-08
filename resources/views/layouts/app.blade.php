<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🐶</text></svg>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        @fluxStyles
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" />

        <flux:heading size="xl"><a href="/" class="flex"><x-svg.logo class="w-6 mr-2"/> {{ config('app.name') }}</a></flux:heading>

        <livewire:ft:header-left-navbar/>

        <flux:spacer />

        <livewire:ft:header-right-navbar/>

    </flux:header>

    <flux:sidebar stashable sticky class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <flux:brand href="/" logo="https://fluxui.dev/img/demo/logo.png" :name="config('app.name')" class="px-2 dark:hidden" />
        <flux:brand href="/" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" :name="config('app.name')" class="px-2 hidden dark:flex" />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" href="#" current>Home</flux:navlist.item>
            <flux:navlist.item icon="inbox" badge="12" href="#">Inbox</flux:navlist.item>
            <flux:navlist.item icon="document-text" href="#">Documents</flux:navlist.item>
            <flux:navlist.item icon="calendar" href="#">Calendar</flux:navlist.item>

            <flux:navlist.group expandable heading="Favorites" class="max-lg:hidden">
                <flux:navlist.item href="#">Marketing site</flux:navlist.item>
                <flux:navlist.item href="#">Android app</flux:navlist.item>
                <flux:navlist.item href="#">Brand guidelines</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="cog-6-tooth" href="#">Settings</flux:navlist.item>
            <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>

    <flux:main container>
        <div class="flex max-md:flex-col items-start">
            @isset($sidebar_navigation)
            <div class="w-full md:w-[220px] pb-4 mr-10">
                <flux:navlist>
                    @foreach ($sidebar_navigation as $item)
                        <flux:navlist.item :href="$item['href']" :badge="$item['badge']" :icon="$item['icon']">{{ $item['text'] }}</flux:navlist.item>
                     @endforeach
                </flux:navlist>
            </div>

            <flux:separator class="md:hidden" />
            @endisset

            <div class="flex-1 max-md:pt-6 self-stretch">
                {{ $header ?? '' }}
                {{ $slot }}

                <flux:separator variant="subtle" />
            </div>
        </div>
        <livewire:ft:footer/>

    </flux:main>

    @persist('toast')
    <flux:toast />
    @endpersist

    @livewireScripts
    @fluxScripts
    </body>

</html>
