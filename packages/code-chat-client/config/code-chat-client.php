<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Chat Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default chat driver that will be used by the
    | chat client. You may set this to any of the drivers defined below.
    |
    */
    'default' => env('CODE_CHAT_DRIVER', 'claude-code'),

    /*
    |--------------------------------------------------------------------------
    | Enable Streaming
    |--------------------------------------------------------------------------
    |
    | This option controls whether streaming responses are enabled by default.
    | When enabled, responses will be streamed in real-time as they are
    | generated rather than waiting for the full response.
    |
    */
    'streaming' => env('CODE_CHAT_STREAMING', true),

    /*
    |--------------------------------------------------------------------------
    | Driver Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for each chat driver. These settings
    | will be passed to the driver when it is created.
    |
    */
    'drivers' => [
        'claude-code' => [
            'timeout' => 120,
            'default_model' => 'claude-3-5-sonnet-20241022',
        ],
    ],
];
