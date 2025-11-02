<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent OS Documentation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @livewire('agent-os-viewer', ['basePath' => base_path(), 'path' => $path ?? ''])
</body>
</html>
