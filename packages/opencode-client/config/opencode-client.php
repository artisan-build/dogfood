<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | OpenCode Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the OpenCode API server. This is typically the local
    | OpenCode CLI TUI server running on port 64415.
    |
    */
    'base_url' => env('OPENCODE_BASE_URL', 'http://127.0.0.1:64415'),
];
