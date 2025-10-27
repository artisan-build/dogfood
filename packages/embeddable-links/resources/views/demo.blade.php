<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embeddable Links Demo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 p-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <flux:heading size="xl" class="mb-8">Embeddable Links Demo</flux:heading>

        <flux:card>
            <flux:heading size="lg" class="mb-4">YouTube Video</flux:heading>
            <x-embeddable-links::embed
                url="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                :cache="false"
            />
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Vimeo Video</flux:heading>
            <x-embeddable-links::embed
                url="https://vimeo.com/148751763"
                :cache="false"
            />
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">GitHub Gist</flux:heading>
            <x-embeddable-links::embed
                url="https://gist.github.com/zeitiger/a8c7eb8b0655ddb3cf834e329da1a1e2"
                :cache="false"
            />
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">OpenGraph Link (Laravel Docs)</flux:heading>
            <x-embeddable-links::embed
                url="https://laravel.com/docs"
                :cache="false"
            />
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Generic Link (Example)</flux:heading>
            <x-embeddable-links::embed
                url="https://example.com"
                :cache="false"
            />
        </flux:card>
    </div>
</body>
</html>
