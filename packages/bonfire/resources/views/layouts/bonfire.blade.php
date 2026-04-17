<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Bonfire' }}</title>

        @fluxAppearance
    </head>
    <body class="h-full bg-white text-zinc-900 antialiased
                 dark:bg-zinc-950 dark:text-zinc-100">
        <div class="flex min-h-full flex-col">
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        @fluxScripts
    </body>
</html>
