<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | OpenCode API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the OpenCode API. By default, this points to the
    | local OpenCode server running on port 3333.
    |
    */
    'base_url' => env('OPENCODE_BASE_URL', 'http://localhost:3333'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in seconds. Default is 30 seconds.
    |
    */
    'timeout' => (int) env('OPENCODE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Optional authentication configuration for the OpenCode API.
    | Currently supports 'bearer' and 'api_key' types.
    |
    */
    'auth' => [
        'type' => env('OPENCODE_AUTH_TYPE'),
        'token' => env('OPENCODE_AUTH_TOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for automatic retry of failed requests.
    | - times: Number of retry attempts
    | - sleep: Milliseconds to wait between retries
    |
    */
    'retry' => [
        'times' => (int) env('OPENCODE_RETRY_TIMES', 3),
        'sleep' => (int) env('OPENCODE_RETRY_SLEEP', 100),
    ],
];
