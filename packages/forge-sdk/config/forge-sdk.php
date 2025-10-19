<?php

declare(strict_types=1);

return [
    /**
     * Laravel Forge API Token
     *
     * You can generate an API token from your Forge account settings.
     */
    'api_token' => env('FORGE_API_TOKEN'),

    /**
     * Default Organization
     *
     * Set a default organization slug or ID to use for all commands.
     * This can be overridden by passing the organization argument to commands.
     * If not set, organization must be provided as a command argument.
     */
    'default_organization' => env('FORGE_ORGANIZATION'),

    /**
     * Default Server
     *
     * Set a default server name or ID to use for all commands.
     * This can be overridden by passing the server argument to commands.
     * If not set, server must be provided as a command argument.
     */
    'default_server' => env('FORGE_SERVER'),

    /**
     * Base URL for the Forge API
     *
     * Default: https://forge.laravel.com/api/v1
     */
    'base_url' => env('FORGE_API_URL', 'https://forge.laravel.com/api/v1'),

    /**
     * Request timeout in seconds
     *
     * Default: 30 seconds
     */
    'timeout' => env('FORGE_TIMEOUT', 30),

    /**
     * Retry configuration for failed requests
     */
    'retry' => [
        /**
         * Number of retry attempts
         */
        'times' => env('FORGE_RETRY_TIMES', 3),

        /**
         * Sleep duration between retries in milliseconds
         */
        'sleep' => env('FORGE_RETRY_SLEEP', 1000),
    ],

    /**
     * Logging configuration
     */
    'logging' => [
        /**
         * Log channel to use for Forge SDK operations
         *
         * Defaults to the application's default log channel.
         * Set to a specific channel (e.g., 'forge') to isolate Forge logs.
         */
        'channel' => env('FORGE_LOG_CHANNEL'),

        /**
         * Log level for Forge operations
         *
         * Options: debug, info, warning, error
         * Default: info
         */
        'level' => env('FORGE_LOG_LEVEL', 'info'),
    ],
];
